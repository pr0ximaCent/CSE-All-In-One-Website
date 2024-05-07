<?php

$dbhost = 'localhost';
$dbname = 'logindb';
$dbusername = 'root';
$dbpass = '';

$mysqli = mysqli_connect($dbhost, $dbusername, $dbpass, $dbname);

if (isset($_POST['post'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $des = $_POST['des'];

    // Prepare the image for insertion
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = "uploads/" . $image_name;
    move_uploaded_file($image_tmp, $image_path);
    $time_12_hour = date("h:i A", strtotime($time));
    // Use prepared statement to insert data
    $stmt = $mysqli->prepare("INSERT INTO ev(title, date, time, des, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $date, $time, $des, $image_path);

    if ($stmt->execute()) {
        header("location:e_post.php");
    } else {
        echo "Failed";
    }

    $stmt->close();
    $mysqli->close();
}

?>




