<?php
session_start();
// Destroy session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Logout</title>
</head>

<body>
    <div class="container">
        <a href=index.php class='btn btn-primary'><h1>HJ News</h1></a>
        <h2>Logged out</h2>
        <p>
            You have successfully logged out.
        </p>
        <a href="index.php" class="btn btn-primary">Click to news page</a>
        <a href="login.php" class="btn btn-primary">Click to login again</a>
    </div>
</body>

</html>