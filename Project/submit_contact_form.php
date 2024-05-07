<?php
// Include your database connection file (e.g., cnct.php)
require_once 'cnct.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Prepare and execute the database query
  $insertQuery = "INSERT INTO contact_submissions (name, email, subject, message) 
                  VALUES ('$name', '$email', '$subject', '$message')";

  if (mysqli_query($conn, $insertQuery)) {
    // Data saved successfully
    echo "<script>alert('Thank you for contacting us');</script>";
} else {
    // Error occurred while saving data
    echo "<script>alert('You response showed error');</script>";
  }

  // Close the database connection
  mysqli_close($conn);
}
?>
