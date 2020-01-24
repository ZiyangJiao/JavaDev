<?php
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//get variables
$shareuser = explode(",",$json_obj['shareuser']);

ini_set("session.cookie_httponly", 1);
session_start();

//test
//$_SESSION['userid'] = 3;

require 'database.php';
// // verify token
// $token = $json_obj['token'];
// if(!hash_equals($_SESSION['token'], $token)) {
//     exit();
// }

//check login status
if(empty($_SESSION['userid'])){
    echo json_encode(array(
        "success" => true,
        "sharedusers" => []
    ));
    //die("Not Login!");
    exit();
}else {
    $userid = $_SESSION['userid'];
}

$stmt = $mysqli->prepare("SELECT ShareUsernames FROM Users WHERE UserID=?");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
// Bind the parameter
$stmt->bind_param('s', $userid);
$stmt->execute();

// Bind the results
$stmt->bind_result($names);
$stmt->fetch();
$stmt->close();

echo json_encode(array(
    "success" => true,
    "sharedusers" => $names
));
exit();
?>