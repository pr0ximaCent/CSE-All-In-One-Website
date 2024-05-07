<?php
require_once 'cnct.php';
session_start();

// Function to move a complaint to the approved table and delete from the complaints table
function approveComplaint($conn, $complaintId) {
  $insertQuery = "INSERT INTO approved_complaints SELECT * FROM complaints WHERE id = $complaintId";
  $deleteQuery = "DELETE FROM complaints WHERE id = $complaintId";

  mysqli_query($conn, $insertQuery);
  mysqli_query($conn, $deleteQuery);

  echo "<script>alert('Complaint has been approved.');</script>";
}

// Check if the "Approve" form is submitted
if (isset($_POST['approve_complaint'])) {
  $complaintId = $_POST['complaint_id'];
  // Approve the complaint
  approveComplaint($conn, $complaintId);
}

// Retrieve pending complaints from the database
$selectQuery = "SELECT c.*, COUNT(v.complaint_id) AS upvotes
                FROM votes AS v
                LEFT JOIN complaints AS c ON c.id = v.complaint_id
                WHERE c.approved = 0
                GROUP BY v.complaint_id
                ORDER BY upvotes DESC";

$result = mysqli_query($conn, $selectQuery);

// Check if there are any pending complaints
if (mysqli_num_rows($result) > 0) {
  $complaints = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
  $complaints = [];
}

mysqli_free_result($result);

// Check if the user is logged in as admin
$isAdmin = false;
if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@cuet.ac.bd') {
  $isAdmin = true;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>All Complaints</title>
  <!-- Include your CSS and JavaScript files -->
  <link rel="stylesheet" href="all_complaint_style.css">
  <link rel="stylesheet" href="button.css">
</head>
<body>
  
<div class="all-complaints-section">
  <h2 style="font-size: 40px;">All Complaints</h2>
  <?php foreach ($complaints as $complaint): ?>
    <div class="complaint">
      <?php if ($complaint['approved'] === '0'): ?>
        <p>#Complaint-id: <?= $complaint['id']; ?></p>
        <p><?= $complaint['text']; ?></p>
        <?php if (!empty($complaint['image_path'])): ?>
          <img src="<?= $complaint['image_path']; ?>" style="height: 400px; width: 500px;" alt="Complaint Image">
        <?php endif; ?>

        <p>Total Upvotes: <?= $complaint['upvotes'] - 1; ?></p>
        <div style="display:flex;flex-direction:row;">
        <form action="upvote.php" method="post">
          <input type="hidden" name="complaint_id" value="<?= $complaint['id']; ?>">
          <button class="custom-btn btn-3" style="margin: 0 auto; margin-right:20px;" type="submit">Upvote</button>
        </form>
        <?php if ($isAdmin): ?>
          <form action="" method="post">
            <input type="hidden" name="complaint_id" value="<?= $complaint['id']; ?>">
            <button class="custom-btn btn-3" style="margin: 0 auto;" type="submit" name="approve_complaint">Approve</button>
          </form>
        <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
