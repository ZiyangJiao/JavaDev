<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload processing</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <?php
    ob_start();
    session_start();
    // header("Content-Type: text/html");

    // Get the filename and make sure it is valid
    $filename = basename($_FILES['uploadedfile']['name']);
    if ($filename == "") {
        echo "<script> {window.alert('You must choose a file before upload. Please retry.');location.href='mainpage.php'} </script>";
        exit;
    }
    if (!preg_match('/^[\w_\.\-]+$/', $filename)) {
        echo "<script> {window.alert('Filename is invalid. Please retry.');location.href='mainpage.php'} </script>";
        exit;
    }

    // Get the username and make sure it is valid
    $username = $_SESSION['username'];
    if (!preg_match('/^[\w_\-]+$/', $username)) {
        echo "<script> {window.alert('Invalid username. Please retry');location.href='mainpage.php'} </script>";
        exit;
    }

    $full_path = sprintf("../../user/%s/%s", $username, $filename);

    if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path)) {
        echo "<script> {window.alert('Upload succeeded');location.href='mainpage.php'} </script>";
        exit;
    } else {
        echo "<script> {window.alert('Upload failed');location.href='mainpage.php'} </script>";
        exit;
    }

    ?>


</body>

</html>