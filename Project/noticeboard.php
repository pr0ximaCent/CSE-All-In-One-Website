<?php
include("notice.php");
$result=mysqli_query($mysqli,"SELECT* from nb ORDER by date DESC");
?>

     <!DOCTYPE html>
        <html>
        
        <head>
          
          <meta charset="utf-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Notice Board</title>
    <script src="https://kit.fontawesome.com/1c9f6d6cb4.js" crossorigin="anonymous"></script>
    <style>
      *{
        margin:0;
        padding:0;
         font-family: 'Roboto', sans-serif;
        box-sizing: border-box;
      }
      .hero{
        width:100%;
        height: 100vh;
        background-image:linear-gradient(rgba(0,0,0,0.5),#596aa8),url(uploads/istockphoto-526059735-612x612.jpg);
        background-position: center;
        background-size: cover;
        display: flex;
        align-items:center ;
        justify-content: center;

      }
      form{
        width: 90%;
        max-width: 600px;
      }
      .input-group{
        margin-bottom: 40px;
        position: relative;

      }
      input,textarea{
        width: 100%;
        padding: 10px;
        outline: 0;
        border: 1px solid #faffff;
        color: #faffff;
        background: transparent;
        font-size: 15px;
      }
      label{
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        padding: 10px;
        color: #faffff;
        cursor: text;
        transition: 0.2s;
      }
      .post-button{
        padding: 10px 0;
        color: #faffff;
        outline: none;
        background:transparent ;
        border: 1px solid #faffff;
        width: 12%;
        cursor: pointer;
      }
      .view-button{
        text-decoration: none;
        font-size:13px;
        padding: 10px 10px 10px;
        color: #faffff;
        outline: none;
        background:transparent ;
        border: 1px solid #faffff;
        cursor: pointer;
        margin-left:450px;
       
      display: inline-block;
       
      }
      .navbar{
        width: 100%;
        background-color: black;
      }
      .navbar-content {
    display: flex;
    justify-content: center;
   
    flex-direction: column;
    text-align: center;
    color:white;
    padding-top: 30px; 
  }
  th,td{
      background-color: black; 
      color:white;
      height: 40px;
    }
    
 
      input:focus~label,input:valid~label,textarea:focus~label,textarea:valid~label{
        top: -35px;
        font-size: 14px;

      }
      input[type="date"]:not(:placeholder-shown) + label {
  top: -35px;
  font-size: 14px;
}


    </style>
        </head>
          <body>
            <div class="hero">
            
               <form action="notice.php" method="POST" enctype="multipart/form-data">
              
                <div class="input-group">
                <input type="text" id="title" name="title" required>
                <label for="title"><i class="fa-solid fa-bell"></i> Title</label>
              </div>
              
              <div class="input-group">
                <input type="text" id="date" name="date" placeholder="Published Date" onfocus="(this.type='date')" required>
                <label for="date"><i class="far fa-calendar-alt"></i>  Published date</label>
              </div>
              
              
              <div class="input-group">
                <input type="text" id="notice" name="notice" required>
                <label for="notice">Notice type</label>
               
              </div>
                      
              

              <div class="input-group">
                <textarea id="des" rows="10" name="des" required></textarea>
                <label for="des"><i class="fa-solid fa-comment"></i>    Description</label>
              </div>
              <div class="input-group">
        <input type="file" id="image" name="image" accept="image/*" required>
        <label for="image"></label>
      </div>
              <button class="post-button" type="post" name="post">POST <i class="fas fa-paper-plane"></i></button>
              <a class="view-button" href="pub.php" name="view">VIEW <i class="fa-solid fa-eye"></i></button>
              </form>
               
            </div>
          
            
           
          </body>