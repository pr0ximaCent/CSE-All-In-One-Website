<?php
require_once 'cnct.php';
session_start();

// Check if the user is logged in as admin

// Retrieve complaints with their total upvotes from the database
$selectQuery = "SELECT * from approved_complaints";
$result = mysqli_query($conn, $selectQuery);

// Check if there are any approved complaints
if (mysqli_num_rows($result) > 0) {
    $approvedComplaints = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $approvedComplaints = [];
}

mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Approved Complaints</title>
    <!-- Include your CSS and JavaScript files -->
    <link rel="stylesheet" href="all_complaint_style.css">
    <link rel="stylesheet" href="button.css">
    <script src="script.js"></script>
    
</head>
<body>
  
  <div class="all-complaints-section">
    <h2 style="font-size: 40px;">Approved Complaints</h2>
    <?php foreach ($approvedComplaints as $complaint): ?>
      <div class="complaint">
        <p>#Complaint-id: <?= $complaint['id']; ?></p>
        <p><?= $complaint['text']; ?></p>
        <?php if (!empty($complaint['image_path'])): ?>
          <img src="<?= $complaint['image_path']; ?>" alt="Complaint Image">
        <?php endif; ?>
        
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
