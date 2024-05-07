<?php
require_once 'cnct.php';
session_start();

$classroomId = $_SESSION['classroomId'];
$attendanceTable = "attendance_" . $classroomId;
$studentId = $_POST['id'];

$query = "UPDATE $attendanceTable SET count = count - 1 WHERE id = $studentId";
$result = mysqli_query($conn, $query);

if ($result) {
    // Update the score based on the attendance count and credit
    $countQuery = "SELECT count FROM $attendanceTable WHERE id = $studentId";
    $countResult = mysqli_query($conn, $countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    $count = $countRow['count'];

    $credit = $_SESSION['credit'];
    $score = $_SESSION['score'];
    $totalMark = $score * 10;
    $present = $score - $count;

    if ($present >= (90 * $score) / 100) {
        $x = ($totalMark * 10) / 100;
    } elseif ($present >= (85 * $score) / 100 && $present < (90 * $score) / 100) {
        $x = ($totalMark * 9) / 100;
    } elseif ($present >= (80 * $score) / 100 && $present < (85 * $score) / 100) {
        $x = ($totalMark * 8) / 100;
    } elseif ($present >= (75 * $score) / 100 && $present < (80 * $score) / 100) {
        $x = ($totalMark * 7) / 100;
    } elseif ($present >= (70 * $score) / 100 && $present < (75 * $score) / 100) {
        $x = ($totalMark * 6) / 100;
    } elseif ($present >= (65 * $score) / 100 && $present < (70 * $score) / 100) {
        $x = ($totalMark * 5) / 100;
    } elseif ($present >= (60 * $score) / 100 && $present < (65 * $score) / 100) {
        $x = ($totalMark * 4) / 100;
    } else {
        $x = 0;
    }

    $updateScoreQuery = "UPDATE $attendanceTable SET score = $x WHERE id = $studentId";
    $updateScoreResult = mysqli_query($conn, $updateScoreQuery);
    if ($updateScoreResult) {
        echo "Attendance and score updated successfully.";
    } else {
        echo "Error updating score: " . mysqli_error($conn);
    }
} else {
    echo "Error updating attendance: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
