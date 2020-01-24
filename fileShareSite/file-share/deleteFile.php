<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>delete file</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <?php
    ob_start();
    session_start();
    header("Content-Type: text/html");

    $filename = $_GET['filename'];
    $full_path = sprintf("../../user/%s/%s", $_SESSION['username'], $filename);
    if (unlink($full_path)) {
        echo "<script> {window.alert('Delete succeeded');location.href='mainpage.php'} </script>";
    } else {
        echo "<script> {window.alert('Delete failed');location.href='mainpage.php'} </script>";
    }


    exit;
    ?>
</body>

</html>