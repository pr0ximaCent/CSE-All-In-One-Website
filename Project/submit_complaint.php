<?php
// config.php
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2MB
$ALLOWED_MIME = ['image/jpeg','image/png','image/gif'];

<?php
// submit_complaint.php
require_once 'cnct.php';
require_once 'config.php';
session_start();

function handleComplaintSubmission($conn) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        return;
    }

    $text      = trim($_POST['complaint_text']);
    $imagePath = null;

    if (!empty($_FILES['complaint_image']['tmp_name'])) {
        $type = mime_content_type($_FILES['complaint_image']['tmp_name']);
        if (in_array($type, $GLOBALS['ALLOWED_MIME']) &&
            $_FILES['complaint_image']['size'] <= MAX_UPLOAD_SIZE) {
            $dest = UPLOAD_DIR . basename($_FILES['complaint_image']['name']);
            move_uploaded_file($_FILES['complaint_image']['tmp_name'], $dest);
            $imagePath = 'uploads/' . basename($dest);
        } else {
            $_SESSION['error'] = 'Invalid image or too large.';
            return;
        }
    }

    $sql  = $imagePath
          ? "INSERT INTO complaints (text, image_path) VALUES (?, ?)"
          : "INSERT INTO complaints (text) VALUES (?)";
    $stmt = mysqli_prepare($conn, $sql);
    $imagePath
        ? mysqli_stmt_bind_param($stmt, "ss", $text, $imagePath)
        : mysqli_stmt_bind_param($stmt, "s", $text);

    if (mysqli_stmt_execute($stmt)) {
        $cid   = mysqli_insert_id($conn);
        $email = $_SESSION['email'];
        $q2    = "INSERT INTO votes (user_email, complaint_id) VALUES (?, ?)";
        $s2    = mysqli_prepare($conn, $q2);
        mysqli_stmt_bind_param($s2, "si", $email, $cid);
        mysqli_stmt_execute($s2);

        $_SESSION['success'] = 'Complaint submitted.';
        header('Location: all_complaints.php');
        exit;
    } else {
        $_SESSION['error'] = 'DB error: ' . mysqli_stmt_error($stmt);
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

handleComplaintSubmission($conn);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Submit Complaint</title>
  <link rel="stylesheet" href="all_complaint_style.css">
  <link rel="stylesheet" href="button.css">
  <link rel="stylesheet" href="submit_complaint_style.css">
</head>
<body>
  <div class="container" style="height:95vh;">
    <?php if (!empty($_SESSION['error'])): ?>
      <p class="error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form method="POST" action="submit_complaint.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <label for="complaint_text">Complaint:</label>
      <textarea id="complaint_text" name="complaint_text" rows="4" required></textarea>
      <label for="complaint_image">Image (optional):</label>
      <input type="file" id="complaint_image" name="complaint_image" accept="image/*">
      <button type="submit" class="custom-btn btn-3">Submit</button>
    </form>
  </div>
</body>
</html>
