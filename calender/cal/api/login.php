<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$password = $json_obj['password'];
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

require 'database.php';

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
// Use a prepared statement---inqury info used to verify identity
$stmt = $mysqli->prepare("SELECT COUNT(*), UserID, PasswordHash FROM Users WHERE Username=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
// Bind the parameter
$stmt->bind_param('s', $username);
$stmt->execute();

// Bind the results
$stmt->bind_result($count, $userid, $pwd_database);
$stmt->fetch();
$stmt->close();
//verify password
if( $count == 1 && password_verify($password, $pwd_database)){
    ini_set("session.cookie_httponly", 1);
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['userid'] = $userid;
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

    echo json_encode(array(
        "success" => true
    ));
    exit;
}else{
    echo json_encode(array(
        "success" => false,
        "message" => "Incorrect Username or Password"
    ));
    exit;
}
?>