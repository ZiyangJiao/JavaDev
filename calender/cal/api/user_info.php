<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
// $json_str = file_get_contents('php://input');
//This will store the data into an associative array
// $json_obj = json_decode($json_str, true);

ini_set("session.cookie_httponly", 1);
session_start();


if (!empty($_SESSION['userid'])) {
    $logged_in = true;
    $username = $_SESSION['username'];
    $userid = $_SESSION['userid'];
    $token = $_SESSION['token'];
}
else {
    $logged_in = false;
    $username = null;
    $userid = null;
    $token = null;
}

echo json_encode(array(
    "success" => true,
    "logged-in" => $logged_in,
    "username" => $username,
    "userid" => $userid,
    "token" => $token
));
exit;
?>
