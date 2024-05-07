<?php
@include 'cnct.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect the user to the login page if not logged in
  header('Location: login.php');
  exit();
}

// Retrieve the student IDs from your database (you'll need to replace this with your own code)
$student_ids = array(1, 2, 3, 4, 5);

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the attendance counts from the form submission
  $counts = $_POST['counts'];

  // Perform any necessary validation on the form data

  // Store the attendance records in the database
  $conn = mysqli_connect('localhost', 'username', 'password', 'attendance');

  // Iterate through the student IDs and update the attendance records
  foreach ($student_ids as $student_id) {
    $count = $counts[$student_id];

    // Insert or update the attendance record
    $date = date('Y-m-d'); // Get the current date
    $sql = "INSERT INTO attendance (student_id, date, count) VALUES ('$student_id', '$date', '$count')
            ON DUPLICATE KEY UPDATE count = '$count'";
    mysqli_query($conn, $sql);
  }

  // Redirect the user back to the profile page or any other desired page
  header('Location: profile.php');
  exit();
}
?>

<!-- HTML code for the attendance page -->
<h1>Take Attendance</h1>

<form action="attendance.php" method="POST">
  <?php foreach ($student_ids as $student_id) { ?>
    <label for="count-<?php echo $student_id; ?>">Student <?php echo $student_id; ?> Count:</label>
    <input type="number" name="counts[<?php echo $student_id; ?>]" id="count-<?php echo $student_id; ?>" min="0" value="0">
    <br>
  <?php } ?>

  <input type="submit" value="Submit Attendance">
</form>
