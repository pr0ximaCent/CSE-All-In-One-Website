<?php
@include 'cnct.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: register.php');
    exit();
}

// Retrieve user details from the database
$email = $_SESSION['email'];
$select = "SELECT * FROM register WHERE email = '$email'";
$result = mysqli_query($conn, $select);

// Check if the user exists
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $designation = $row['designation'];
} else {
    // Redirect to login page if user not found
    header('Location: register.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="card.css">
    <title>Profile | Ludiflex</title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        $(document).ready(function() {
          // Change Password button click event
          
          $('#changePasswordBtn').click(function() {
            var newPassword = prompt('Enter new password:');
            if (newPassword) {
              $('input[name="newpassword"]').val(newPassword);
            }
          });

          // Change Designation button click event
          $('#changeDesignationBtn').click(function() {
            var newDesignation = prompt('Enter new designation:');
            if (newDesignation) {
              $('input[name="designation"]').val(newDesignation);
            }
          });
        });
        function goToSelectedPage() {
            var dropdown = document.getElementById("navDropdown");
            var selectedOption = dropdown.options[dropdown.selectedIndex].value;

            if (selectedOption !== "") {
                window.location.href = selectedOption;
            }
        }
    </script>
</head>
<body>
    <div class="card">
        <div class="ds-top"></div>
        <div class="avatar-holder">
            <img src="images/<?=$row['image']?>" id="imagePreview">
        </div>
        <div class="name gap">
        <h3><?=$row['username']?></h3>
        <p><?php echo $email; ?></p>
            <p>Designation: <?=$designation?></p>
        </div>

        <div class="ds-info">
          <div>
            <input type="submit" name="cngp" class="custom-button"id="changePasswordBtn" value="Change Password">
            <input type="submit" name="cngd" class="custom-button" id="changeDesignationBtn" value="Change Designation">
           
            <select id="navDropdown" onchange="goToSelectedPage()">
        <option value="">Select an option</option>
        <option value="create_class.php">Take Attendance</option>
        <option value="submit_complaint.php">Submit Complaint</option>
        <option value="all_complaints.php">View Complaints</option>
        <option value="noticeboard.php">Publish Notice</option>
        <option value="event.php">Post Event</option>
        <option value="contact_me.php">Contacted</option>
    </select>

            <form method="POST" action="ad_update.php" enctype="multipart/form-data">
              <input type="file" name="image" onchange="previewImage(event)">
              <br>
              <input type="hidden" name="designation" value="" id="newDesignationInput">
                <input type="hidden" name="newpassword" value="" id="newPasswordInput">
              <input type="submit" name="update" class="custom-button" value="Update">
            </form>
            
            <form method="POST" action="logout.php">
              <input type="submit" name="logout" class="custom-button" value="Logout">
            </form>
          </div>
        </div>
    </div>
</body>
</html>
