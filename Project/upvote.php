<?php
require_once 'cnct.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the complaint ID from the submitted form
  $complaintId = $_POST['complaint_id'];

  // Get the user's email (assuming it's stored in a session)
  $userEmail = $_SESSION['email']; // Replace with the appropriate session variable

  // Check if the user has already upvoted for this complaint
  $checkQuery = "SELECT * FROM votes WHERE user_email = '$userEmail' AND complaint_id = $complaintId";
  $checkResult = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
    // User has already upvoted, handle the appropriate action
    // For example, display an error message or redirect back to the complaints page
    echo "<script>alert('You have already upvoted for this complaint.');</script>";
  } else {
    // User has not upvoted yet, proceed with upvoting
    $upvoteQuery = "INSERT INTO votes (user_email, complaint_id) VALUES ('$userEmail', $complaintId)";
    $upvoteResult = mysqli_query($conn, $upvoteQuery);

    if ($upvoteResult) {
      // Update the vote_count in the complaints table
      $updateQuery = "UPDATE complaints SET vote_count = vote_count + 1 WHERE id = $complaintId";
      $updateResult = mysqli_query($conn, $updateQuery);

      if ($updateResult) {
        // Fetch the updated vote count from the complaints table
        $selectQuery = "SELECT vote_count FROM complaints WHERE id = $complaintId";
        $selectResult = mysqli_query($conn, $selectQuery);

        if ($selectResult && mysqli_num_rows($selectResult) > 0) {
          $voteCount = mysqli_fetch_assoc($selectResult)['vote_count'];

          // Upvote successful, handle the appropriate action
          // For example, display a success message or redirect back to the complaints page
          echo "<script>alert('Upvoted successfully. Total upvotes: $voteCount');</script>";
        } else {
          // Error in fetching the updated vote count, handle the appropriate action
          // For example, display an error message or redirect back to the complaints page
          echo "<script>alert('Error in fetching the updated vote count.');</script>";
        }
      } else {
        // Error in updating the vote count, handle the appropriate action
        // For example, display an error message or redirect back to the complaints page
        echo "<script>alert('Error in updating vote count.');</script>";
      }
    } else {
      // Error in upvoting, handle the appropriate action
      // For example, display an error message or redirect back to the complaints page
      echo "<script>alert('Error in upvoting.');</script>";
    }
  }
}

mysqli_close($conn);
?>
