<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{$error = array(); // Initialize an empty array for errors

@include 'cnct.php';

if (isset($_POST['submit'])) {
    $regname = mysqli_real_escape_string($conn, $_POST['regname']);
    $regmail = mysqli_real_escape_string($conn, $_POST['regmail']);
    $regpass = mysqli_real_escape_string($conn,$_POST['regpass']);
    $cregpass = mysqli_real_escape_string($conn,$_POST['cregpass']);
 

    $select = mysqli_query($conn, "SELECT * FROM `register` WHERE email = '$regmail' AND password = '$regpass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $error[] = 'user already exist';
    } else {
        if ($regpass != $cregpass) {
            $error[] = 'Passwords do not match!';
        } 
        elseif (strpos($regmail, 'cuet.ac.bd') === false) { 
            $error[] = 'Please insert email address containing cuet.ac.bd';
        } 
       
        else {
            $insert = mysqli_query($conn, "INSERT INTO `register`(username, email, password,c_password) VALUES('$regname', '$regmail', '$regpass','$cregpass')") or die('query failed');
    
            if($insert){
                $error[] = 'registered successfully!';
               
            }else{
                $error[] = 'registeration failed!';
            }
        }
    }
}

if (isset($_POST['login'])) {
    $error = array();
    
    $regmail = mysqli_real_escape_string($conn, $_POST['mail']);
    $regpass = mysqli_real_escape_string($conn,$_POST['pass']);
    

    $select = "SELECT * FROM register WHERE email = '$regmail' && password = '$regpass' ";

    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) == 0) {
        $error[] = 'Incorrect email or password!';
    } else {
        $row = mysqli_fetch_assoc($result);
        $userEmail = $row['email'];
        $userPass = $row['password'];
        $userName = $row['username'];
        session_start();
        $_SESSION['email'] = $userEmail;
        $_SESSION['username']=$userName;
        if($userEmail=='admin@cuet.ac.bd' && $userPass=='1234')
        header('Location: admin.php');
        else
        header('Location: profile.php');
        exit();
    }
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ltyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Login | Ludiflex</title>
</head>
<body>
<video id="video-container" src="vid1.mp4" autoplay loop muted></video>
    <div class="container">
        <div class="box">
            <!-- Login Box -->
            <div class="box-login" id="login">
                <div class="top-header">
                    <h3>Hello, Again</h3>
                    <small>We are happy to have you back.</small>
                </div>
                <form action="" method="POST">
                    <div class="input-group">
                        <div class="input-field">
                            <input type="text" name="mail" class="input-box" id="logEmail" required>
                            <label for="logEmail">Email address</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="pass" class="input-box" id="logPassword" required>
                            <label for="logPassword">Password</label>
                            <div class="eye-area">
                                <div class="eye-box" onclick="myLogPassword()">
                                    <i class="fa-regular fa-eye" id="eye"></i>
                                    <i class="fa-regular fa-eye-slash" id="eye-slash"></i>
                                </div>
                            </div>
                        </div>
                       
                        <div class="input-field">
                            <input type="submit" class="input-submit" name="login" value="Sign In">
                        </div>
                        <div class="forgot">
                            <a href="contact.html">Forgot password?</a>
                            <p><a href="#" onclick="goToIndexPage()">Back</a></p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Register -->
            <?php
                if(isset($error)){
                    foreach($error as $error){
                        echo "<script>alert('$error');</script>";
                    }
                }
            ?>
            <div class="box-register" id="register">
                <div class="top-header">
                    <h3>Sign Up, Now</h3>
                    <small>We are happy to have you with us.</small>
                </div>
                <form action="" method="POST">
                    <div class="input-group">
                        <div class="input-field">
                            <input type="text" name="regname" class="input-box" id="regUser" required>
                            <label for="regUser">Username</label>
                        </div>
                        <div class="input-field">
                            <input type="text" name="regmail" class="input-box" id="regEmail" required>
                            <label for="regEmail">Email address</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="regpass" class="input-box" id="regPassword" required>
                            <label for="regPassword">Password</label>
                            <div class="eye-area">
                                <div class="eye-box" onclick="myRegPassword()">
                                    <i class="fa-regular fa-eye" id="eye-2"></i>
                                    <i class="fa-regular fa-eye-slash" id="eye-slash-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="input-field">
                            <input type="password" name="cregpass" class="input-box" id="cregPassword" required>
                            <label for="cregPassword">Confirm Your Password</label>
                            <div class="eye-area">
                                <div class="eye-box" onclick="myRegPassword()">
                                    <i class="fa-regular fa-eye" id="eye-3"></i>
                                    <i class="fa-regular fa-eye-slash" id="eye-slash-3"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="input-field">
                            <input type="submit" class="input-submit" name="submit" value="Register">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Switch -->
            <div class="switch">
                <a href="#" class="login" onclick="login()">Login</a>
                <a href="#" class="register" onclick="register()">Register</a>
                <div id="btn"></div>
            </div>
        </div>
    </div>

    <script>
        var x = document.getElementById('login');
        var y = document.getElementById('register');
        var z = document.getElementById('btn');

        function login() {
            x.style.left = "27px";
            y.style.right = "-350px";
            z.style.left = "0px";
          
        }

        function register() {
            x.style.left = "-350px";
            y.style.right = "25px";
            z.style.left = "150px";
            
        }

        function myLogPassword() {
            var a = document.getElementById('logPassword');
            var b = document.getElementById('eye');
            var c = document.getElementById('eye-slash');

            if (a.type === "password") {
                a.type = "text";
                b.style.opacity = "0";
                c.style.opacity = "1";
            } else {
                a.type = "password";
                b.style.opacity = "1";
                c.style.opacity = "0";
            }
        }
        function goToIndexPage() {
            window.location.href = "index.html";
        }
        function myRegPassword() {
            var d = document.getElementById('regPassword');
            var e = document.getElementById('cregPassword');
            var b = document.getElementById("eye-2");
            var c = document.getElementById("eye-slash-2");
            var f = document.getElementById("eye-3");
            var g = document.getElementById("eye-slash-3");

            if (d.type === "password" && e.type === "password") {
                d.type = "text";
                e.type = "text";
                b.style.opacity = "1";
                c.style.opacity = "0";
                f.style.opacity = "1";
                g.style.opacity = "0";
            } else {
                d.type = "password";
                e.type = "password";
                b.style.opacity = "0";
                c.style.opacity = "1";
                f.style.opacity = "0";
                g.style.opacity = "1";
               
            }
        }
    </script>
</body>
</html>
