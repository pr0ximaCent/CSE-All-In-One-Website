<?php
require_once 'cnct.php';

$studentId = $_POST['studentId'];
$studentName = $_POST['studentName'];
$classroomId = $_POST['classroomId'];

// Check if the provided classroom ID is valid
$checkQuery = "SELECT * FROM classrooms WHERE id = $classroomId";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
  $attendanceTable = "attendance_" . $classroomId;

  $insertQuery = "INSERT INTO $attendanceTable (student_id, name, count) VALUES ('$studentId', '$studentName', 30)";
  $insertResult = mysqli_query($conn, $insertQuery);

  if ($insertResult) {
    echo "Student attendance added successfully.";
  } else {
    echo "Error adding student attendance: " . mysqli_error($conn);
  }
} else {
  echo "Invalid classroom ID.";
}

mysqli_close($conn);
?>
