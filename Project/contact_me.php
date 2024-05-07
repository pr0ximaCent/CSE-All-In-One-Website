<?php
// Include the database connection file (modify the connection details as needed)
require_once 'cnct.php';

// Retrieve contact submissions from the database
$selectQuery = "SELECT * FROM contact_submissions";
$result = mysqli_query($conn, $selectQuery);

// Check if there are any contact submissions
if (mysqli_num_rows($result) > 0) {
    $contactSubmissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $contactSubmissions = [];
}

mysqli_free_result($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Contact Submissions</title>
    <style>
        /* Style the container and table */
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

/* Style table header */
th {
    background-color: gray;
    padding: 10px;
    text-align: left;
    color:black;
}

/* Style table rows */
td {
    padding: 10px;
    border-bottom: 1px solid #ccc;
    color:black;
}

/* Style alternate rows */
tr:nth-child(even) {
    background-color: #f2f2f2;
}


        @media (max-width: 500px) {
            th, td {
                font-size: 12px;
            }
        }

        </style>
</head>
<body>
    <div class="container">
        <h2>Contact Submissions</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Submission Date</th>
            </tr>
            <?php foreach ($contactSubmissions as $submission): ?>
                <tr>
                    <td><?= $submission['name']; ?></td>
                    <td><?= $submission['email']; ?></td>
                    <td><?= $submission['subject']; ?></td>
                    <td><?= $submission['message']; ?></td>
                    <td><?= $submission['submission_date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
