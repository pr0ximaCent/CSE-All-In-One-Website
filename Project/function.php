<?php
include("notice.php");
               if(isset($_POST['post']))
           {    
         $title = $_POST['title'];
         $date = $_POST['date'];
         
         $room = $_POST['room'];
         $notice = $_POST['notice'];
         $des = $_POST['des'];
         $result=mysqli_query($mysqli,"INSERT INTO nb values('','$title','$date','$room','$notice','$des')");
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