<?php
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json


//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$eventid = $json_obj['eventid'];
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
// verify token
 $token = $json_obj['token'];
 if(!hash_equals($_SESSION['token'], $token)) {
     exit();
 }

//check login status
if(empty($_SESSION['userid'])){
    exit();
}else {
    $userid = $_SESSION['userid'];
}

//$userid = 3;

//check event if belong to current user
$checkname = $mysqli->prepare("SELECT UserID FROM Events WHERE EventID=?");
if (!$checkname) {
    printf("Check Event Name Query Prep Failed: %s\n", $mysqli->error);
    exit();
}
$checkname->bind_param('s', $eventid);
$checkname->execute();
$checkname->bind_result($checkuserid);
$checkname->fetch();
if($checkuserid != $_SESSION['userid']){
    echo json_encode(array(
        "success" => false,
        "message" => "Cannot delete/edit event (owned by" . strval($checkuserid) . ") not owned by you (" . $_SESSION['userid'] . ")"
    ));
    $checkname->close();
    exit();
}
$checkname->close();


// Insert into Events table
$stmt = $mysqli->prepare("DELETE FROM Events WHERE EventID=? and UserID =?");

if (!$stmt) {
    echo json_encode(array(
        "success" => false,
        "message" => "sql prepare failed!" . $stmt->error
    ));
    exit;
}
$stmt->bind_param('ii', $eventid, $userid);

if(!$stmt->execute()){
    echo json_encode(array(
        "success" => false,
        "message" => "sql execute failed!" . $mysqli->error
    ));
    exit;
}
else {
    // $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true
    ));
}
exit;
?>
