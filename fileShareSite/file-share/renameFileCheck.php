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
    <title>Rename processing</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <?php
    $username = $_SESSION['username'];
    $fileNameOld = $_POST['fileNameOld'];
    $fileNameNew = $_POST['fileNameNew'];

    // Check if new file name is valid
    if ((!preg_match('/^[\w_\.\-]+$/', $fileNameNew)) || ($fileNameNew == "")) {
        echo "<script> {window.alert('New name is invalid. Please retry.');location.href='renameFile.php'} </script>";
        exit;
    } else {
        $fileSource = "../../user/" . $username . "/";
        if (rename($fileSource . $fileNameOld, $fileSource . $fileNameNew)) {
            echo "<script> {window.alert('Rename succeeded. Click to nagivate to file page');location.href='mainpage.php'} </script>";
        } else {
            echo "<script> {window.alert('Rename failed. Click to retry.');location.href='renameFile.php'} </script>";
            exit;
        }
    }


    ?>

</body>

</html>