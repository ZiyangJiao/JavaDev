<?php
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login processing...</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <?php
    // Get username
    $username = trim($_POST['username']);

    // Filter username
    if (!preg_match('/^[\w_\-]+$/', $username)) {
        // Warn user if username invalid
        echo "<script> {window.alert('Invalid username. Please check and retry');location.href='login.php'} </script>";
        exit;
    }

    // Open username file
    $user = fopen("../../user/users.txt", "r");

    // error=1: username not exists
    // error=1: username exists
    $error = 1;
    while (!feof($user)) {
        // If username matches, redirect to mainpage
        if ($username == trim(fgets($user))) {
            $error = 0;
            $_SESSION['username'] = $username;
            header("Location: mainpage.php");
            break;
        }
    }

    // Warn user if username not exists
    if ($error == 1) {
        echo "<script> {window.alert('Username does not exist. Please retry or click register');location.href='login.php'} </script>";
        exit;
    }

    fclose($user);
    ?>


</body>

</html>