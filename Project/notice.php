
     

<?php

$dbhost = 'localhost';
$dbname = 'logindb';

$dbusername = 'root';
$dbpass = '';


$mysqli = mysqli_connect($dbhost,$dbusername,$dbpass,$dbname,);

if(isset($_POST['post']))
{    
$title = $_POST['title'];
$date = $_POST['date'];


$notice = $_POST['notice'];
$des = $_POST['des'];
$image_name = $_FILES['image']['name'];
  $image_tmp = $_FILES['image']['tmp_name'];
  $image_path = "uploads/" . $image_name;
  move_uploaded_file($image_tmp, $image_path);

  
  $result = mysqli_query($mysqli, "INSERT INTO nb (title, date, notice, des, image) VALUES ('$title', '$date', '$notice', '$des', '$image_path')");


if($result)
{
header("location:noticeboard.php");
}
else
{
 echo "Failed";
  }
 }

	
?>
