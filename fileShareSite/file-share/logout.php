<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Logout user</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <?php
    session_start();
    // Destroy session
    session_destroy();
    echo "<script> {window.alert('Logout succeeded. Click to navigate to login page.');location.href='login.php'} </script>";
    exit;

    ?>
</body>

</html>