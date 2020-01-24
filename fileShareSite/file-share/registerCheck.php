<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register processing</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    // Get username
    <?php
    $username = trim($_POST['username']);
    // Filter username
    // Warn user if username illegal
    if (!preg_match('/^[\w_\-]+$/', $username)) {
        echo "<script> {window.alert('Invalid username. Please check and retry');location.href='registerUser.php'} </script>";
        exit;
    }

    // File and directory to store user information
    $userDb =  "../../user/users.txt";
    $userDbDir = "../../user/";

    $user = fopen($userDb, "r+");
    // error=0: username non-exists
    $error = 0;
    while (!feof($user)) {
        if ($username == trim(fgets($user))) {
            // error==1: username already exists
            $error = 1;
            echo "<script> {window.alert('Username already exists. Please use it to login directly');location.href='login.php'} </script>";
            exit;
        }
    }
    if ($error == 0) {
        // Append username into username file
        fwrite($user, $username . "\n");
        // Create directory for new user
        mkdir($userDbDir . $username);
        echo "<script> {window.alert('Register succeeded. Please use it to login');location.href='login.php'} </script>";
        exit;
    }
    fclose($user);
    ?>

</body>

</html>