<?php
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$joinReason = $_POST['joinReason'];
$paymentMethod = $_POST['paymentMethod'];
$transactionNumber = $_POST['transactionNumber'];

//database connection
//http://localhost:8080/phpmyadmin/
//http://127.0.0.1:5500/connect.php
$servername = "127.0.0.1:5500";
$username = "root";
$password = "";
$database = "form";

$conn = new mysqli($servername, $username, $password, $database);

	if($conn->connect_error){
		echo "$conn->connect_error";
		die("Connection Failed : ". $conn->connect_error);
	} else {
		$stmt = $conn->prepare("insert into join(firstName, lastName, email, phone, address,joinReason,paymentMethod,transactionNumber) values(?, ?, ?, ?,?,?, ?, ?)");
		$stmt->bind_param("sssissss", $firstName, $lastName, $email, $phone,$address,$joinReason,$paymentMethod,$transactionNumber);
		$execval = $stmt->execute();
		echo $execval;
		echo " Registration successfully...";
		$stmt->close();
		$conn->close();
	}
?>