<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$event_title = $json_obj['event_title'];
$event_content = $json_obj['event_content'];
$event_start = $json_obj['event_start'];
$event_end = $json_obj['event_end'];
$event_tag = $json_obj['event_tag'];
$event_group = $json_obj['event_group'];
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';

 //verify token
$token = $json_obj['token'];

if(!hash_equals($_SESSION['token'], $token)) {
    die("Illegally Request! ");
}

//check login status
if(empty($_SESSION['userid'])){
    exit();
}else {
    $userid = $_SESSION['userid'];
}

//test
//$userid = 3;

$group_filter = null;
if(!empty($event_group)) {
    $group = explode(",", $event_group);

    if(!empty($group)) {
        foreach ($group as $name) {
            $checkname = $mysqli->prepare("SELECT COUNT(*) FROM Users WHERE Username=?");
            if (!$checkname) {
                printf("Check Name Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Bind the parameter
            $checkname->bind_param('s', $name);
            $checkname->execute();

            // Bind the results
            $checkname->bind_result($count);
            $checkname->fetch();
            $checkname->close();

            // check group name
            if ($count != 1) {
                $group_filter = null;
                //group name error
                echo json_encode(array(
                    "success" => false,
                    "message" => "Names in Shared Group error!"
                ));
                exit;
            }

            $group_filter = $group_filter . $name . ",";

        }
    }
}

// Insert into Events table
$stmt = $mysqli->prepare("insert into Events (UserID, EventTitle, EventContent, EventStartTime, EventEndTime, Tag, GroupUsernames) values (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(array(
        "success" => false,
        "message" => "sql prepare failed!"
    ));
    exit;
}
$stmt->bind_param('issssss', $userid, $event_title, $event_content, $event_start, $event_end, $event_tag, $group_filter);
if(!$stmt->execute()){
    echo json_encode(array(
        "success" => false,
        "message" => "sql execute failed!" . $mysqli->error
    ));
    $stmt->close();
    exit;
}else{
    echo json_encode(array(
        "success" => true
    ));
    $stmt->close();
    exit;
}

?>
