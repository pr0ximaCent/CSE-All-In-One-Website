<?php
require_once 'cnct.php';
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get complaint details from the form (trim whitespace)
    $complaintText = trim($_POST['complaint_text']);

    // Check if an image is uploaded
    if (isset($_FILES['complaint_image']) && $_FILES['complaint_image']['error'] === UPLOAD_ERR_OK) {
        $imageFile = $_FILES['complaint_image']['tmp_name'];
        $imagePath = 'images/' . basename($_FILES['complaint_image']['name']);
        move_uploaded_file($imageFile, $imagePath);

        // Insert complaint with image path
        $insertQuery = "INSERT INTO complaints (text, image_path) VALUES (?, ?)";
        $statement   = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($statement, "ss", $complaintText, $imagePath);
    } else {
        // Insert complaint without image
        $insertQuery = "INSERT INTO complaints (text) VALUES (?)";
        $statement   = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($statement, "s", $complaintText);
    }

    // Execute the statement
    if (mysqli_stmt_execute($statement)) {
        $complaintId  = mysqli_insert_id($conn);
        $userEmail    = $_SESSION['email'];

        // Record initial vote
        $voteInsertQuery  = "INSERT INTO votes (user_email, complaint_id) VALUES (?, ?)";
        $voteStatement    = mysqli_prepare($conn, $voteInsertQuery);
        mysqli_stmt_bind_param($voteStatement, "si", $userEmail, $complaintId);
        mysqli_stmt_execute($voteStatement);
        mysqli_stmt_close($voteStatement);

        echo "<script>
                if (confirm('Complaint submitted successfully')) {
                    window.location.href = 'all_complaints.php';
                }
              </script>";
    } else {
        echo "Error: " . mysqli_stmt_error($statement);
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

      <label for="complaint_image">Image:</label>
      <input type="file" id="complaint_image" name="complaint_image">

      <button type="submit" class="custom-btn btn-3">Submit</button>
    </form>
  </div>
</body>
</html>
