<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <!-- Login -->
    <h1>Login</h1>
    Please enter your username to login.
    <form name="userInfo" action="loginCheck.php" method="post">
        <p>username:<input type="text" name="username" /></p>
        <input type="submit" value="Login" />
    </form>

    <!-- Register -->
    <hr>
    <h1>Register</h1>
    If you do not yet have an account, click <a href="registerUser.php">here</a> to register.

</body>

</html>