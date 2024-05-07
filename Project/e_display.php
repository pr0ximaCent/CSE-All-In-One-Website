<?php
include("event.php");
$result=mysqli_query($mysqli,"SELECT* from ev ORDER by date DESC");
?>
<head>
<style>
.navbar, .table-container {
  opacity: 0;
  animation: fadeIn 1s ease-in-out forwards;
}

@keyframes fadeIn {
  to {
    opacity: 1;
  }
}

.navbar {
  width: 100%;
  height:10vh;
  background: linear-gradient(to right, #8acdd3, #06BBCC);
 margin-top:-12px;
  padding-bottom:3px;
  
}

.navbar-content {
  display: flex;
  
  font-size:20px;
  justify-content: center;
  flex-direction: column;
  text-align: center;
  color: white;
  

}

.table-container {
  width: 100%;
  overflow-x: auto;
}

table {
  border-collapse: collapse;
  width: 100%;
  font-size: 20px;
 
}

th, td {
  border: 3px solid darkblue ;
  padding: 10px;
  text-align: center;
  font-size: 20px;
}
td {
  border: 3px solid darkblue ;
  padding: 10px;
  text-align: center;
  font-size: 20px;
}
table tr td:first-child,
table tr th:first-child {
  border-left: none;
}

table tr td:last-child,
table tr th:last-child {
  border-right: none;
}
.description-scroll {
  white-space: nowrap;
  overflow: hidden;
}

.description-scroll marquee {
  width: 100%;
}
body {
    font-family: 'Roboto', sans-serif;
    
  margin: 0;
  background-image: url(uploads/winter-blue-pink-gradient-background-vector_53876-117276.avif);
  background-size: cover;
  background-position: center;
  
}

table tr:hover {
  background-color: rgba(104, 114, 196, 0.4);
  transition: background-color 0.3s ease-in-out;
}
.image-cell {
              
            max-width: 200px; 
            overflow: hidden;
        }
        .image-cell img {
            width: 100%;
            height:8vh;
            display: block;
        }
</style>
</head>

<body>
<div>
  <div class="navbar-content">
    <nav class="navbar navbar-light fs-3 mb-5 ;">
   
    <h2>Upcoming Events</h2>
    </nav>
  </div>
</div>

<div class="table-container">
  <table>
    <tr>
      <th style="width: 15%;">Event Title</th>
      <th style="width: 12%;">Event Date</th>
      
      <th style="width: 10%;">Event Time</th>
      <th style="width: 6%;">Image</th>
      <th>Details</th>
      
    </tr>
    <?php
    while ($res = mysqli_fetch_array($result)) {
      echo '<tr>';
      echo '<td style="text-align: center;">' . $res['title'] . '</td>';
      echo '<td style="text-align: center;">' . $res['date'] . '</td>';

      $time_12_hour = date("h:i A", strtotime($res['time']));
    echo '<td style="text-align: center;">' . $time_12_hour . '</td>';

      
     

      

echo '<td class="image-cell"><a href="' . $res['image'] . '" target="_blank"><img src="' . $res['image'] . '" alt="Image"></a></td>';



      

      echo '<td class="description-scroll"><marquee behavior="scroll" direction="left">' . $res['des'] . '</marquee></td>';
      echo '</tr>';
    }
    ?>
  </table>
</div>
</body>

