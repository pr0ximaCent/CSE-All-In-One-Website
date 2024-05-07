<?php
// Include cnct.php if required for database connection
@include 'cnct.php';

session_start();
session_destroy();
header('Location: register.php');
exit();
?>
