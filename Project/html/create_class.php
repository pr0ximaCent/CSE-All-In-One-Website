<?php
require_once 'cnct.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $className = $_POST['class_name'];
    $action = $_POST['action'];

    if ($action === 'create') {
        // Insert classroom details into the "classrooms" table
        $credit = $_POST['credit']; // Get the credit value
        $_SESSION['credit'] = $credit;
        $checkQuery = "SELECT id FROM classrooms WHERE name = '$className'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>alert('Classroom name already exists. Please choose a different name.');</script>";
        } else {
            $insertQuery = "INSERT INTO classrooms (name, credit) VALUES ('$className', $credit)"; // Insert credit value
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                // Retrieve the last inserted classroom ID
                $classroomId = mysqli_insert_id($conn);

                // Create a new table for attendance
                $attendanceTable = "attendance_" . $classroomId; // Generate a unique table name for attendance
                $createTableQuery = "CREATE TABLE IF NOT EXISTS $attendanceTable (
                    id INT(11) PRIMARY KEY,
                    count INT(11),
                    score INT(11) DEFAULT ($credit*10)
                )";
                $createTableResult = mysqli_query($conn, $createTableQuery);

                if ($createTableResult) {
                    // Redirect to the attendance page for the created classroom
                    $_SESSION['classroomId'] = $classroomId;
                    $_SESSION['score'] = $credit * 10;
                    header("Location: attendance.php");
                    exit();
                } else {
                    echo "Error creating attendance table: " . mysqli_error($conn);
                }
            } else {
                echo "Error creating classroom: " . mysqli_error($conn);
            }
        }
    } elseif ($action === 'join') {
        // Check if the classroom exists
        $checkQuery = "SELECT id FROM classrooms WHERE name = '$className'";
        $checkResult = mysqli_query($conn, $checkQuery);
        $credit = $_POST['credit'];
        $_SESSION['score'] = $credit * 10;

        // Set the credit value in the session
        $_SESSION['credit'] = $credit;

        if (mysqli_num_rows($checkResult) > 0) {
            $classroomData = mysqli_fetch_assoc($checkResult);
            $classroomId = $classroomData['id'];

            // Redirect to the attendance page for the joined classroom
            $_SESSION['classroomId'] = $classroomId;
            header("Location: attendance.php");
            exit();
        } else {
            echo "Classroom not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Orchid</title>
    <!-- Web Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,900,700,500,300' rel='stylesheet' type='text/css'>
    <!-- Flaticon CSS -->
    <link href="fonts/flaticon/flaticon.css" rel="stylesheet">
    <!-- font-awesome CSS -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!-- Offcanvas CSS -->
    <link href="css/hippo-off-canvas.css" rel="stylesheet">
    <!-- animate CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- owl.carousel CSS -->
    <link href="css/owl.theme.css" rel="stylesheet">
    <link href="css/owl.carousel.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Responsive CSS -->
    <link href="css/responsive.css" rel="stylesheet">

    <script src="js/vendor/modernizr-2.8.1.min.js"></script>
    <!-- HTML5 Shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="js/vendor/html5shim.js"></script>
        <script src="js/vendor/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar">

    <div id="st-container" class="st-container">
        <div class="st-pusher">
            <div class="st-content">
                <div class="st-content-inner">

                    <header class="header">
                        <nav class="navbar navbar-default" role="navigation">
                            <div class="container">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="navbar-collapse">
                                    <ul class="nav navbar-nav navbar-right">
                                        <li><a class="page-scroll" href="#home">Home</a></li>
                                        <li><a class="page-scroll" href="#services">Services</a></li>
                                        <li><a class="page-scroll" href="#work">Works</a></li>
                                        <li><a class="page-scroll" href="#about">About</a></li>
                                        <li><a class="page-scroll" href="#blog">Blog</a></li>
                                        <li><a class="page-scroll" href="#contact">Contact</a></li>
                                    </ul>
                                </div><!-- /.navbar-collapse -->
                            </div><!-- /.container -->
                        </nav>
                    </header>

                    <!-- slider content -->
                    <div id="x-corp-carousel" class="carousel slide hero-slide" data-ride="carousel">
                        <!-- Indicators -->


                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img src="img/slider/slide-1.jpg" alt="Hero Slide">
                                <!--Slide Image-->

                                <div class="container">

                                    <div class="carousel-caption">
                                        <h1 class="animated lightSpeedIn">Create/Join Classroom</h1>

                                        <p class="lead animated lightSpeedIn">Online attendance system of Chittagong University of Engineering and Technology</p>
                                        <form method="POST" action="create_class.php" class="form-inline">
                                            <div class="form-group">
                                                <label for="class_name" style="font-size: 20px;">Class Name:</label>
                                                <input type="text" id="class_name" name="class_name" required class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="credit" style="font-size: 20px;">Credit:</label>
                                                <input type="text" id="credit" name="credit" required class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <input type="radio" id="action_create" name="action" value="create" checked>
                                                <label for="action_create">Create</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="radio" id="action_join" name="action" value="join">
                                                <label for="action_join">Join</label>
                                            </div>
                                            <button type="submit" class="btn btn-default">Submit</button>
                                        </form>
                                    </div>

                                    <!--.carousel-caption-->
                                </div>
                                <!--.container-->
                            </div>
                            <!--.item-->

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Waypoints -->
    <script src="js/jquery.waypoints.min.js"></script>
    <!-- CountTo -->
    <script src="js/jquery.countTo.js"></script>
    <!-- Isotope -->
    <script src="js/isotope.pkgd.min.js"></script>
    <!-- WOW -->
    <script src="js/wow.min.js"></script>
    <!-- Owl Carousel -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- Custom JS -->
    <script src="js/script.js"></script>

</body>

</html>
