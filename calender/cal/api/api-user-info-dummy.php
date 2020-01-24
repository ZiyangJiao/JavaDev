<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
header('Access-Control-Allow-Origin: *');

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
// $json_str = file_get_contents('php://input');
//This will store the data into an associative array
// $json_obj = json_decode($json_str, true);

if (rand(1, 10) <= 15) {
    $logged_in = true;
    $username = 'user1';
    $userid = 3;
}
else {
    $logged_in = false;
    $username = null;
    $userid = null;
}

echo json_encode(array(
    "success" => true,
    "logged-in" => $logged_in,
    "username" => $username,
    "userid" => $userid,
    "token" => "token-from-api-user-info-dummy"
));
exit;
?>
