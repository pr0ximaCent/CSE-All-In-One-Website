<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{$error = array(); // Initialize an empty array for errors

@include 'cnct.php';

if (isset($_POST['submit'])) {
    $regname = mysqli_real_escape_string($conn, $_POST['regname']);
    $regmail = mysqli_real_escape_string($conn, $_POST['regmail']);
    $regpass = mysqli_real_escape_string($conn,$_POST['regpass']);
    $cregpass = mysqli_real_escape_string($conn,$_POST['cregpass']);
    $regcheck = isset($_POST['regcheck']) ? 1 : 0;

    $select = "SELECT * FROM register WHERE email = '$regmail'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($regpass != $cregpass) {
            $error[] = 'Passwords do not match!';
        } 
        if (!filter_var($regmail, FILTER_VALIDATE_EMAIL)) { // Use strict comparison operator (===)
            $error[] = 'Invalid Email';
        } 
        if (!empty($error)) {

            foreach ($error as $message) {
                echo $message . '<br>';
            }
        }
        else {
            $insert = "INSERT INTO register(username, email, password, c_password, regremember_me) VALUES('$regname','$regmail','$regpass','$cregpass','$regcheck')";
            mysqli_query($conn, $insert);
            header('Location: register.php');
            exit(); // Exit the script after redirecting
        }
    }
}

if (isset($_POST['login'])) {
    $error = array();
    
    $regmail = mysqli_real_escape_string($conn, $_POST['mail']);
    $regpass = md5($_POST['pass']);
    $regcheck = isset($_POST['check']) ? 1 : 0;

    $select = "SELECT * FROM register WHERE email = '$regmail' && password = '$regpass' ";

    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) == 0) {
        $error[] = 'Incorrect email or password!';
    } else {
        $row = mysqli_fetch_assoc($result);
        $userEmail = $row['email'];
        session_start();
        $_SESSION['email'] = $userEmail;
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
                        <div class="remember">
                            <input type="checkbox" name="check" id="formCheck" class="check">
                            <label for="formCheck">Remember Me</label>
                        </div>
                        <div class="input-field">
                            <input type="submit" class="input-submit" name="login" value="Sign In">
                        </div>
                        <div class="forgot">
                            <a href="#">Forgot password?</a>
                            <p><a href="#" onclick="goToIndexPage()">Back</a></p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Register -->
            <?php
            if (isset($error) && !empty($error)) {
                foreach ($error as $errMsg) {
                    echo '<span class="error-msg">' . $errMsg . '</span>';
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
                        <div class="remember">
                            <input type="checkbox" name="regcheck" id="formCheck-2" class="check">
                            <label for="formCheck-2">Remember Me</label>
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
                b.style.opacity = "0";
                c.style.opacity = "1";
                f.style.opacity = "0";
                g.style.opacity = "1";
            } else {
                d.type = "password";
                e.type = "password";
                b.style.opacity = "1";
                c.style.opacity = "0";
                f.style.opacity = "1";
                g.style.opacity = "0";
            }
        }
    </script>
</body>
</html>
