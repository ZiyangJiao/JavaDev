<?php
session_start();
require '../../user/database.php';

// If POST --- ready to process user's register info
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //filter input
    $username = $mysqli->real_escape_string(trim($_POST['username']));

    // Check if username already exists
    // SQL injection escape | $mysqli->real_escape_string
    $username = $mysqli->real_escape_string($username);
    $res_userid = $mysqli->query("select UserID from Users where Username = '" . $username . "'");
    $row_userid = $res_userid->fetch_assoc();
    $userid = $row_userid['UserID'];
    if (isset($userid)) {
        echo "<script> {window.alert('Username already exists. Please use it to login or choose a different username');location.href='register.php'} </script>";
        exit();
    }

    // Check if username already exists
    // WHY BELOW NOT WORKING ???!!! --- might related to mysql_escape_string, which works with $mysqli->query
    // $stmt = $mysqli->prepare("select UserID from Users where Username = ?");
    // if (!$stmt) {
    //     printf("Query Prep Failed: %s\n", $mysqli->error);
    //     exit;
    // }
    // $stmt->bind_param('s', $username);
    // $stmt->execute();
    // $stmt->bind_result($userid);
    // $stmt->close();

    // Insert into Users table
    $password = trim($_POST['password']);

    $stmt = $mysqli->prepare("insert into Users (Username, PasswordHash) values (?, ?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param('ss', $username, $password_hash);
    $stmt->execute();
    $stmt->close();

    // Get user's userid for later use
    $res_userid = $mysqli->query("select UserID from Users where Username = '" . $username . "'");
    $row_userid = $res_userid->fetch_assoc();
    $userid = $row_userid['UserID'];

    // Get user's userid for later use
    // WHY BELOW NOT WORKING ???!!!
    // $stmt = $mysqli->prepare("select UserID from Users where Username = ?");
    // if (!$stmt) {
    //     printf("Query Prep Failed: %s\n", $mysqli->error);
    //     exit;
    // }
    // $stmt->bind_param('s', $username);
    // $stmt->execute();
    // $stmt->bind_result($userid);
    // $stmt->close();

    $_SESSION['userid'] = $userid;
    $_SESSION['username'] = $username;
    // Generate token
    $_SESSION['token'] = bin2hex(random_bytes(32));
    echo "<script> {window.alert('Register succeeded. Click to view news');location.href='index.php'} </script>";
    exit;
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
    <title>Register</title>
</head>

<body>
    <div class="container">
        <a href=index.php class='btn btn-primary'>
            <h1>HJ News</h1>
        </a>
        <!-- Login -->
        <h2>Register</h2>
        <form name="register" action="register.php" onsubmit="return verify(this);" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Choose a username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Choose a password">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" class="form-control" placeholder="Type the password again">
            </div>
            <div>
                <input type="hidden" name="token" value="<?php bin2hex(random_bytes(32)); ?>" />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Login -->
        <hr>
        <h2>Login</h2>
        <p>If you have already got an account and would like to log in</p>
        <a href="login.php" class="btn btn-primary">Click to login</a>

        <!-- Guest -->
        <hr>
        <h2>Guest mode</h2>
        <p>To view news as a guest</p>
        <a href="index.php" class="btn btn-primary">Click to view news</a>
    </div>

    <script>
        //creative portion: form verify at frontend
        function verify(userInfo) {
            with(register) {
                if (password.value != password_confirm.value || password.value == "") {
                    window.alert("Passwords are not the same");
                    return false;
                }
                if (username.value == "") {
                    window.alert("Username cannot be empty");
                    return false;
                }
            }
            return true;
        }
    </script>
</body>

</html>