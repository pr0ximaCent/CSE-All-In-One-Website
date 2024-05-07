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
} else {
    // Redirect to login page if user not found
    header('Location: register.php');
    exit();
}
if (isset($_POST['cngp'])) {
    if (isset($_POST['newpassword']) && !empty($_POST['newpassword'])) {
        $newPassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
        $updateQuery = "UPDATE register SET password = '$newPassword'  WHERE email = '$email'";
        $updateResult = mysqli_query($conn, $updateQuery);
        
    }
    
}
if (isset($_POST['cngd'])) {
    if (isset($_POST['designation']) && !empty($_POST['designation'])) {
        $newdes = mysqli_real_escape_string($conn, $_POST['designation']);
        $updateQuery = "UPDATE register SET designation = '$newdes'  WHERE email = '$email'";
        $updateResult = mysqli_query($conn, $updateQuery);
        
    }
    
}
// Handle form submission
if (isset($_POST['update'])) {
    // Retrieve form inputs
    if (isset($_POST['newpassword']) && !empty($_POST['newpassword'])) {
        $newPassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
        $updateQuery = "UPDATE register SET password = '$newPassword'  WHERE email = '$email'";
        $updateResult = mysqli_query($conn, $updateQuery);
        
    }
    if (isset($_POST['designation']) && !empty($_POST['designation'])) {
        $newdes = mysqli_real_escape_string($conn, $_POST['designation']);
        $updateQuery = "UPDATE register SET designation = '$newdes'  WHERE email = '$email'";
        $updateResult = mysqli_query($conn, $updateQuery);
        
    }
   

    // Handle file upload
    $image = $row['image']; // Default image
    
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'images/' . $image;

        // Move the uploaded file to the desired location
       
        $updateQuery = "UPDATE register SET image = '$image' WHERE email = '$email'";
        $updateResult = mysqli_query($conn, $updateQuery);
   
    }
    header('Location: profile.php');

    // Update user profile
    
}

// Close the database connection
mysqli_close($conn);
?>
