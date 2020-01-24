<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <h1>Register</h1>
    Please enter the username you'd like to use
    <!-- Pass username to registerCheck.php to check -->
    <form name="registerInfo" action="registerCheck.php" method="post">

        <p>Username: <input type="text" name="username" /></p>

        <input type="submit" value="Register Now" />
    </form>

    <br>

</body>

</html>