<?php
require_once 'cnct.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaintText = trim($_POST['complaint_text']);

    // Allowed image MIME types and max size
    $allowedTypes = ['image/jpeg','image/png','image/gif'];
    $maxSize      = 2 * 1024 * 1024; // 2MB

    if (isset($_FILES['complaint_image']) && $_FILES['complaint_image']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['complaint_image']['tmp_name']);
        finfo_close($fileInfo);

        if (in_array($mimeType, $allowedTypes) && $_FILES['complaint_image']['size'] <= $maxSize) {
            $imagePath = 'images/' . basename($_FILES['complaint_image']['name']);
            move_uploaded_file($_FILES['complaint_image']['tmp_name'], $imagePath);

            $insertQuery = "INSERT INTO complaints (text, image_path) VALUES (?, ?)";
            $statement   = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($statement, "ss", $complaintText, $imagePath);
        } else {
            die('Invalid image file or too large. Max 2MB, JPG/PNG/GIF only.');
        }
    } else {
        $insertQuery = "INSERT INTO complaints (text) VALUES (?)";
        $statement   = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($statement, "s", $complaintText);
    }

    if (mysqli_stmt_execute($statement)) {
        $complaintId = mysqli_insert_id($conn);
        $userEmail   = $_SESSION['email'];

        $voteQuery   = "INSERT INTO votes (user_email, complaint_id) VALUES (?, ?)";
        $voteStmt    = mysqli_prepare($conn, $voteQuery);
        mysqli_stmt_bind_param($voteStmt, "si", $userEmail, $complaintId);
        mysqli_stmt_execute($voteStmt);
        mysqli_stmt_close($voteStmt);

        header('Location: all_complaints.php?success=1');
        exit;
    } else {
        echo "Submission error: " . mysqli_stmt_error($statement);
    }

    mysqli_stmt_close($statement);
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
      <label for="complaint_text">Complaint:</label>
      <textarea id="complaint_text" name="complaint_text" rows="4" required></textarea>

      <label for="complaint_image">Image (optional, max 2MB):</label>
      <input type="file" id="complaint_image" name="complaint_image" accept=".jpg,.jpeg,.png,.gif">

      <button type="submit" class="custom-btn btn-3">Submit</button>
    </form>
  </div>
</body>
</html>
