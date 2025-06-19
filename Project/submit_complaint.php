<?php
require_once 'cnct.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    $complaintText = trim($_POST['complaint_text']);
    $allowedTypes  = ['image/jpeg','image/png','image/gif'];
    $maxSize       = 2 * 1024 * 1024;
    $imagePath     = null;

    if (!empty($_FILES['complaint_image']['tmp_name'])) {
        $mimeType = mime_content_type($_FILES['complaint_image']['tmp_name']);
        if (in_array($mimeType, $allowedTypes) && $_FILES['complaint_image']['size'] <= $maxSize) {
            $imagePath = 'images/' . basename($_FILES['complaint_image']['name']);
            move_uploaded_file($_FILES['complaint_image']['tmp_name'], $imagePath);
        } else {
            $_SESSION['error'] = 'Invalid image or size exceeds 2MB.';
            header('Location: submit_complaint.php');
            exit;
        }
    }

    // Build query dynamically
    if ($imagePath) {
        $query = "INSERT INTO complaints (text, image_path) VALUES (?, ?)";
        $stmt  = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $complaintText, $imagePath);
    } else {
        $query = "INSERT INTO complaints (text) VALUES (?)";
        $stmt  = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $complaintText);
    }

    if (mysqli_stmt_execute($stmt)) {
        $complaintId = mysqli_insert_id($conn);
        $email       = $_SESSION['email'];

        $voteSQL = "INSERT INTO votes (user_email, complaint_id) VALUES (?, ?)";
        $voteSt  = mysqli_prepare($conn, $voteSQL);
        mysqli_stmt_bind_param($voteSt, "si", $email, $complaintId);
        mysqli_stmt_execute($voteSt);
        mysqli_stmt_close($voteSt);

        $_SESSION['success'] = 'Complaint submitted successfully.';
        header('Location: all_complaints.php');
        exit;
    } else {
        $_SESSION['error'] = 'Submission error: ' . mysqli_stmt_error($stmt);
        header('Location: submit_complaint.php');
        exit;
    }
}

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
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

      <label for="complaint_text">Complaint:</label>
      <textarea id="complaint_text" name="complaint_text" rows="4" required></textarea>

      <label for="complaint_image">Image (optional):</label>
      <input type="file" id="complaint_image" name="complaint_image" accept="image/*">

      <button type="submit" class="custom-btn btn-3">Submit</button>
    </form>
  </div>
</body>
</html>
