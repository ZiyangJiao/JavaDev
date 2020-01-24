<?php
session_start();
require '../../user/database.php';

// If POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //filter and get username
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Use a prepared statement---inqury info used to verify identity
    $stmt = $mysqli->prepare("SELECT COUNT(*), UserID, PasswordHash FROM Users WHERE Username=?");

    // Bind the parameter
    $stmt->bind_param('s', $username);
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($count, $userid, $pwd_database);
    $stmt->fetch();

    //verify password
    if ($count == 1 && password_verify($password, $pwd_database)) {
        // Login succeeded!
        $_SESSION['userid'] = $userid;
        $_SESSION['username'] = $username;
        // Generate token
        $_SESSION['token'] = bin2hex(random_bytes(32));
        header('location: index.php');
    } elseif (($count == 1 && !password_verify($password, $pwd_database))) {
        // Login failed --password is not correct; redirect back to the login screen
        echo "<script> {window.alert('Username or password is not correct. Please check and retry');location.href='login.php'} </script>";
        exit;
    } else {
        // Login failed --username is not existed; redirect back to the register screen
        echo "<script> {window.alert('Username is not registered. Please register');location.href='register.php'} </script>";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <a href=index.php class='btn btn-primary'><h1>HJ News</h1></a>
        <!-- Login -->
        <h2>Login</h2>
        <form name="login" action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Your username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Your password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Register -->
        <hr>
        <h2>Register</h2>
        <p>If you would like to create an account</p>
        <a href="register.php" class="btn btn-primary">Click to register</a>

        <!-- Guest -->
        <hr>
        <h2>Guest mode</h2>
        <p>To view news as a guest</p>
        <a href="index.php" class="btn btn-primary">Click to view news</a>
    </div>
</body>

</html>