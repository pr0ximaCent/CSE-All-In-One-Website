<?php
require_once 'cnct.php';
session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    $complaintText = trim($_POST['complaint_text']);
    $allowedTypes  = ['image/jpeg','image/png','image/gif'];
    $maxSize       = 2 * 1024 * 1024;

    if (isset($_FILES['complaint_image']) && $_FILES['complaint_image']['error'] === UPLOAD_ERR_OK) {
        $mimeType = mime_content_type($_FILES['complaint_image']['tmp_name']);
        if (in_array($mimeType, $allowedTypes) && $_FILES['complaint_image']['size'] <= $maxSize) {
            $imagePath = 'images/' . basename($_FILES['complaint_image']['name']);
            move_uploaded_file($_FILES['complaint_image']['tmp_name'], $imagePath);

            $query = "INSERT INTO complaints (text, image_path) VALUES (?, ?)";
            $stmt  = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $complaintText, $imagePath);
        } else {
            die('Invalid image or size exceeds 2MB.');
        }
    } else {
        $query = "INSERT INTO complaints (text) VALUES (?)";
        $stmt  = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $complaintText);
    }

    if (mysqli_stmt_execute($stmt)) {
        $complaintId = mysqli_insert_id($conn);
        $email       = $_SESSION['email'];

        $voteQ = "INSERT INTO votes (user_email, complaint_id) VALUES (?, ?)";
        $voteS = mysqli_prepare($conn, $voteQ);
        mysqli_stmt_bind_param($voteS, "si", $email, $complaintId);
        mysqli_stmt_execute($voteS);
        mysqli_stmt_close($voteS);

        header('Location: all_complaints.php?success=1');
        exit;
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
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
