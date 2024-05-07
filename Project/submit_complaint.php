<?php
require_once 'cnct.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get complaint details from the form
  $complaintText = $_POST['complaint_text'];

  // Check if an image is uploaded
  if (isset($_FILES['complaint_image']) && $_FILES['complaint_image']['error'] === UPLOAD_ERR_OK) {
    // Process the uploaded image
    $imageFile = $_FILES['complaint_image']['tmp_name'];
    $imagePath = 'images/' . $_FILES['complaint_image']['name'];

    // Move the uploaded image to the desired location
    move_uploaded_file($imageFile, $imagePath);

    // Prepare the statement to insert complaint details into the database with the image path
    $insertQuery = "INSERT INTO complaints (text, image_path) VALUES (?, ?)";
    $statement = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($statement, "ss", $complaintText, $imagePath);

    // Execute the statement
    if (mysqli_stmt_execute($statement)) {
      // Get the complaint ID of the inserted complaint
      $complaintId = mysqli_insert_id($conn);

      // Insert user email and complaint ID into the votes table
      $userEmail = $_SESSION['email']; // Replace with the appropriate session variable
      $voteInsertQuery = "INSERT INTO votes (user_email, complaint_id) VALUES ('$userEmail', $complaintId)";
      $voteInsertResult = mysqli_query($conn, $voteInsertQuery);

      if ($voteInsertResult) {
        // Complaint submitted successfully
        echo "<script>
                if (window.confirm('Complaint submitted successfully')) {
                    window.location.href = 'all_complaints.php';
                }
             </script>";
      } else {
        // Error in inserting into the votes table
        echo "Error inserting into the votes table: " . mysqli_error($conn);
      }
    } else {
      // Error in executing the statement
      echo "Error submitting complaint: " . mysqli_stmt_error($statement);
    }

    mysqli_stmt_close($statement);
  } else {
    // No image uploaded
    // Prepare the statement to insert complaint details into the database without the image
    $insertQuery = "INSERT INTO complaints (text) VALUES (?)";
    $statement = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($statement, "s", $complaintText);

    // Execute the statement
    if (mysqli_stmt_execute($statement)) {
      // Get the complaint ID of the inserted complaint
      $complaintId = mysqli_insert_id($conn);

      // Insert user email and complaint ID into the votes table
      $userEmail = $_SESSION['email']; // Replace with the appropriate session variable
      $voteInsertQuery = "INSERT INTO votes (user_email, complaint_id) VALUES ('$userEmail', $complaintId)";
      $voteInsertResult = mysqli_query($conn, $voteInsertQuery);

      if ($voteInsertResult) {
        // Complaint submitted successfully
        echo "<script>
                if (window.confirm('Complaint submitted successfully')) {
                    window.location.href = 'all_complaints.php';
                }
             </script>";
      } else {
        // Error in inserting into the votes table
        echo "Error inserting into the votes table: " . mysqli_error($conn);
      }
    } else {
      // Error in executing the statement
      echo "Error submitting complaint: " . mysqli_stmt_error($statement);
    }

    mysqli_stmt_close($statement);
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="all_complaint_style.css">
  <link rel="stylesheet" href="button.css">
  <link rel="stylesheet" href="submit_complaint_style.css">
  <title>Document</title>
</head>
<body>
  <div class="container" style = "height:95vh;">
    <div style="display:flex;align-items:center;flex-direction:column;">
      <form method="POST" action="submit_complaint.php" enctype="multipart/form-data" style="display:flex;align-items:center;flex-direction:column;">
        <label for="complaint_text">Complaint:</label>
        <textarea id="complaint_text" name="complaint_text" rows="4" required></textarea>

        <label for="complaint_image">Image:</label>
        <input type="file" id="complaint_image" name="complaint_image">

        <button type="submit" class="custom-btn btn-3" onclick="window.location.href='all_complaints.php'">Submit</button>
      </form>
    </div>
  </div>
</body>
</html>
