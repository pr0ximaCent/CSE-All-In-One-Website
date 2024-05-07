<?php
require_once 'cnct.php';
session_start();

$classroomId = $_SESSION['classroomId']; // Retrieve the classroom ID from the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission for adding student IDs to the attendance list
    
    $startId = $_POST['start_id'];
    $endId = $_POST['end_id'];
    
    // Insert student IDs within the given range into the attendance table
    $attendanceTable = "attendance_" . $classroomId;
    $insertQuery = "INSERT INTO $attendanceTable (id, count) VALUES ";
    for ($id = $startId; $id <= $endId; $id++) {
        $insertQuery .= "('$id', 0),";
    }
    $insertQuery = rtrim($insertQuery, ','); // Remove the trailing comma
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        // Return a success response
        echo "<script>alert('Student IDs added successfully.');</script>";
        
    } else {
        // Return an error response
        echo "Error adding student IDs: " . mysqli_error($conn);
    }
    exit();
}

// Fetch the attendance data for the classroom
$attendanceTable = "attendance_" . $classroomId;
$selectQuery = "SELECT * FROM $attendanceTable";
$selectResult = mysqli_query($conn, $selectQuery);

if (!$selectResult) {
    echo "Error fetching attendance: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="attendance.css">
  <link rel="stylesheet" type="text/css" href="button.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <title>Online Attendance</title>
  <script>
    function increaseCount(studentId) {
      $.ajax({
        url: 'update_attendance.php',
        type: 'POST',
        data: { id: studentId },
        success: function(response) {
          // Reload the attendance data after successful update
          fetchAttendance();
        },
        error: function(xhr, status, error) {
          console.log('Error:', error);
        }
      });
    }
   
    function dcnt(studentId) {
      $.ajax({
        url: 'dupdate_attendance.php',
        type: 'POST',
        data: { id: studentId },
        success: function(response) {
          // Reload the attendance data after successful update
          fetchAttendance();
        },
        error: function(xhr, status, error) {
          console.log('Error:', error);
        }
      });
    }
    function addStudents() {
      var startIdInput = $('#start-id');
      var endIdInput = $('#end-id');

      var startId = parseInt(startIdInput.val().trim());
      var endId = parseInt(endIdInput.val().trim());

      if (!isNaN(startId) && !isNaN(endId) && startId <= endId) {
        $.ajax({
          url: 'attendance.php',
          type: 'POST',
          data: { start_id: startId, end_id: endId },
          success: function(response) {
            // Clear the input fields and display the response
            startIdInput.val('');
            endIdInput.val('');
            $('#response-message').text(response);
            fetchAttendance();
          },
          error: function(xhr, status, error) {
            console.log('Error:', error);
          }
        });
      }
    }

    // Function to fetch the attendance data from the server
    function fetchAttendance() {
      $.ajax({
        url: 'fetch_attendance.php',
        type: 'GET',
        success: function(response) {
          $('#attendance-container').html(response);
        },
        error: function(xhr, status, error) {
          console.log('Error:', error);
        }
      });
    }

    // Initial fetch of attendance data
    $(document).ready(function() {
      fetchAttendance();
    });
  </script>
</head>
<body>
<!-- <video id="video-container" src="vid1.mp4" autoplay loop muted></video> -->
  <div id="attendance-container"></div>
  <div style="display: flex;justify-content: center;padding-bottom:2rem;height:50vh;padding-top:4rem;">
    <button type="button" class="custom-btn btn-3" style="margin-right:2rem; " data-toggle="modal" data-target="#addStudentModal">Add Student</button>
    <button type="button" class="custom-btn btn-3"  onclick="window.location.href='create_class.php'">Back</button>
  
  </div>
  <div style="display: flex;justify-content: center;">
  
  </div>
  <!-- Add Student Modal -->
  <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="display: flex; justify-content: center;">
          <h5 class="modal-title" id="addStudentModalLabel">Add Students by ID Range</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="start-id">Start ID:</label>
            <input type="text" class="form-control" id="start-id" placeholder="Enter start ID">
          </div>
          <div class="form-group">
            <label for="end-id">End ID:</label>
            <input type="text" class="form-control" id="end-id" placeholder="Enter end ID">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn bt-primary" onclick="addStudents(); $('#addStudentModal').modal('hide')">Save</button>
        </div>
      </div>
    </div>
  </div>
  <div id="response-message"></div> <!-- Display response message here -->

  <script>
    // Attach event listeners to dynamically generated buttons
    $(document).on('click', '.count-button', function() {
      var studentId = $(this).data('student-id');
      increaseCount(studentId);
    });

    $(document).on('click', '.cntdec', function() {
      var studentId = $(this).data('student-id');
      dcnt(studentId);
    });

  
  </script>
</body>
</html>
