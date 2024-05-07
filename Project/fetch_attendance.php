<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="attendance.css">
  <style>
    .center-table {
      display: flex;
      justify-content: center;
      min-height: 60vh;
    }
    /* Card container */

.table-container {
  display: grid;
  justify-content: center;
  grid-template-rows: auto 1fr auto; /* Define three rows: auto, 1fr (flexible), and auto */
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 20px;
  max-width: 500px;
  background-color: rgba(224, 229, 236, 0.8);
  max-height: 70vh; /* Set a fixed height for the table container */
  flex-grow: 1; /* Allow the table container to grow in height */
     
  overflow-y: auto;
  }

/* Add this style to table to make it scrollable */


  </style>
</head>
<body>
<?php
require_once 'cnct.php';

session_start(); // Start the session

if (isset($_SESSION['classroomId'])) {
  $classroomId = $_SESSION['classroomId'];
  $attendanceTable = "attendance_" . $classroomId;

  $query = "SELECT * FROM $attendanceTable";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    echo "<div  style='padding-top:5rem;display:flex;justify-content: center;'>";
    echo "<div class='table-container'>"; // Added the table container
    echo "<table class='center-table'>";
    echo "<tr><th>Student ID</th><th>Count</th><th style='padding-left: 20px;'>Absent</th><th>Score</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
      $studentId = $row['id'];
      $count = $row['count'];
      $credit = $_SESSION['credit']; // Retrieve the credit from the session
      $score = $_SESSION['score']; // Calculate the initial score based on the credit
      $tot_mark = $score * 10;
      $present = $score - $count;
      if($present>=(90*$score)/100)
      {$x=($tot_mark*10)/100;
      }
      elseif ($present>=(85*$score)/100 && $present<(90*$score)/100) {
        $x=($tot_mark*9)/100;
      }
      elseif ($present>=(80*$score)/100 && $present<(85*$score)/100) {
        $x=($tot_mark*8)/100;
      }
      elseif ($present>=(75*$score)/100 && $present<(80*$score)/100) {
        $x=($tot_mark*7)/100;
      }
      elseif ($present>=(70*$score)/100 && $present<(75*$score)/100) {
        $x=($tot_mark*6)/100;
      }
      elseif ($present>=(65*$score)/100 && $present<(70*$score)/100) {
        $x=($tot_mark*5)/100;
      }
      elseif ($present>=(60*$score)/100 && $present<(65*$score)/100) {
        $x=($tot_mark*4)/100;
      }
      else
      {
        $x=0;
      }
      echo "<tr>";
      echo "<td>$studentId</td>";
      echo "<td>$count</td>";
      echo "<td>";
      echo "<button class='button count-button' style='margin-right: 5px;' data-student-id='$studentId'>+</button>";
      echo "<button class='button cntdec' style='margin-left: 5px;' data-student-id='$studentId'>-</button>";
      echo "</td>";
      echo "<td>$x</td>";
      echo "</tr>";
    }

    echo "</table>";
    echo "</div>"; // Close the table container
    echo "</div>";
  } else {
    echo "No students found";
  }
} else {
  echo "Classroom ID not specified";
}

mysqli_close($conn);
?>
</body>
</html>
