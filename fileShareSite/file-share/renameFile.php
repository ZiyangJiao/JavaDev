<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rename a file</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <?php
    $username = $_SESSION['username'];
    echo "Current user: $username";
    ?>
    <br>
    Click <a href="logout.php">here</a> to logout
    <hr>

    <h1>Rename an existing file</h1>
    Choose a file by its name, type in the new name and press the "Rename" button
    <hr>

    <?php
    $fileSource = "../../user/" . $username . "/";
    $fileList = scandir($fileSource);
    ?>

    <!-- Display all files under user's directory -->
    Existing file
    <form action="renameFileCheck.php" method="POST">
        <select name="fileNameOld" >
            <?php
            foreach ($fileList as $i) {
                if (($i != ".") && ($i != "..")) {
                    echo "<option value=$i>$i</option>";
                }
            }
            ?>
        </select>
        <br>
        <label>New name <input type="text" name="fileNameNew"></label>
        <input type="submit" value="Rename">
    </form>

</body>

</html>