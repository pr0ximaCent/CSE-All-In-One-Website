<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Annotation list</title>
    <link rel="stylesheet" href="annote.css">
    
    <script src="app.js"></script>
</head>
<body>
    <header>
        <a href="index.php" class="noref">
            <div class="refr">
                <span class="refrs">Research</span>Forces
            </div>
        </a>

        <div class="profile">
            <img class="avatar" src="<?php echo $image; ?>">
            <form action="" class="nvsign" method="post">
                <input type="submit" class="ssnvsign" name='secbutton' />
            </form>
            <p class="nvsign"> | </p>
            <form action="" class="nvsign" method="post">
                <input type="submit" class="ssnvsign" name='sectextbutton' />
            </form>
        </div>
    </header>
    
    <button>List</button>

    <div class="card">
        <h2>Form Card</h2>
        <form action="#" method="post" enctype="multipart/form-data">
            <div style="display: block;">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" placeholder="Enter the title" required>
            </div>

            <br><br>

            <div style="display: block;">
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Enter the description" rows="4" required></textarea>
            </div>
            <br><br>
            
            <div style="display: block;">
                <label for="instruction">Instruction:</label>
                <textarea id="instruction" name="instruction" placeholder="Enter the instruction" rows="4" required></textarea>
            </div>
            <br><br>

            <div style="display: block;">
                <label for="file">File Upload:</label>
                <input type="file" id="file" name="file" required>
            </div>

            <div style="display: block;">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
