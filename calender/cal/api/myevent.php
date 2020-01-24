<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
require 'sql.sql';  // File for sql

//$userid = 3;
//$username = "user";

//check login status
if(empty($_SESSION['userid'])){
//if(empty($userid)){
    echo json_encode(array(
        "success" => true,
        "EventID" => [],
        "EventTitle" => [],
        "EventContent" => [],
        "EventStartTime" => [],
        "EventEndTime" => [],
        "Tag" => [],
        "GroupUsernames" => []
    ));
    //die("Not Login!");
    exit();
}else {
    $userid = $_SESSION['userid'];
    $username = $_SESSION['username'];
}
// echo $json_obj['token'];
// printf("\n");
// echo $_SESSION['token'];
// verify token
//$token = $json_obj['token'];
//if(!hash_equals($_SESSION['token'], $token)) {
//    exit();
//}


$eventIdArray = array();
$eventTitleArray = array();
$eventContentArray = array();
$eventStartTimeArray = array();
$eventEndTimeArray = array();
$eventTagArray = array();
$eventGroupUsernames = array();

// search all event
$d1 = $json_obj['d1'];  // Search after  0:00:00 on this date
$d2 = $json_obj['d2'];  // Search before 0:00:00 on this date

// find events of user own
$stmt = $mysqli->prepare($sql_event);
if (!$stmt) {
    echo json_encode(array(
        "success" => false,
        "message" => "sql prepare failed!" . htmlspecialchars($mysqli->error)
    ));
    exit;
}
$stmt->bind_param('issss', $userid, $d1, $d2, $d1, $d2);
if(!$stmt->execute()){
    echo json_encode(array(
        "success" => false,
        "message" => "sql execute failed!" . $mysqli->error
    ));
    $stmt->close();
    exit;
}
$stmt->bind_result($event_id, $event_title, $event_content, $event_start_time, $event_end_time, $tag, $group_usernames);
while($stmt->fetch()){
    $eventIdArray[] = htmlentities($event_id);
    $eventTitleArray[] = htmlentities($event_title);
    $eventContentArray[] = htmlentities($event_content);
    $eventStartTimeArray[] = htmlentities($event_start_time);
    $eventEndTimeArray[] = htmlentities($event_end_time);
    $eventTagArray[] = htmlentities($tag);
    $eventGroupUsernames[] = htmlentities($group_usernames);
}
$stmt->close();

// find events of shared by other users
$stmt = $mysqli->prepare("SELECT Username, UserID, ShareUsernames FROM Users WHERE UserID!=?");
if (!$stmt) {
    echo json_encode(array(
        "success" => false,
        "message" => "sql prepare failed!" . htmlspecialchars($mysqli->error)
    ));
    exit;
}
$stmt->bind_param('i', $userid);
if(!$stmt->execute()){
    echo json_encode(array(
        "success" => false,
        "message" => "sql execute failed!" . $mysqli->error
    ));
    $stmt->close();
    exit;
}
$stmt->bind_result($username_temp, $userid_temp, $ShareUsernames_temp);
$username_temp_array = array();
$userid_temp_array = array();
$ShareUsernames_temp_array = array();
while($stmt->fetch()){
    $username_temp_array[] = $username_temp;
    $userid_temp_array[] = $userid_temp;
    $ShareUsernames_temp_array[] = $ShareUsernames_temp;
}
$stmt->close();

$rgx = "/\b".$username."\b/";
for($i = 0; $i < count($userid_temp_array); ++$i){
    if (preg_match($rgx, $ShareUsernames_temp_array[$i])) {
        $share = $mysqli->prepare($sql_event);
        if (!$share) {
            echo json_encode(array(
                "success" => false,
                "message" => "sql prepare failed!" . htmlspecialchars($mysqli->error)
            ));
            exit;
        }
        $share->bind_param('issss', $userid_temp_array[$i], $d1, $d2, $d1, $d2);
        $share->execute();
        if (!$share->execute()) {
            echo json_encode(array(
                "success" => false,
                "message" => "sql execute failed!" . $mysqli->error
            ));
            $share->close();
            exit;
        }
        $share->bind_result($event_id, $event_title, $event_content, $event_start_time, $event_end_time, $tag, $group_usernames);
        while ($share->fetch()) {
            $eventIdArray[] = htmlentities($event_id);
            $eventTitleArray[] = htmlentities($event_title);
            $eventContentArray[] = htmlentities($event_content);
            $eventStartTimeArray[] = htmlentities($event_start_time);
            $eventEndTimeArray[] = htmlentities($event_end_time);
            $eventTagArray[] = "Shared-by-" . htmlentities($username_temp_array[$i]);
            $eventGroupUsernames[] = htmlentities($group_usernames);
        }
        $share->close();

    }
}


// find events of shared by groups
//$stmt = $mysqli->prepare("SELECT Events.Username,Events.UserID, Events.GroupUsernames, Events.EventID,Events.EventTitle, Events.EventContent, Events.EventStartTime, Events.EventEndTime FROM Events  WHERE Events.UserID!=? AND ((EventEndTime IS NOT NULL
//        AND EventEndTime > ?
//        AND EventEndTime < ?)
//       OR (EventStartTime > ?
//           AND EventStartTime < ?))");

$stmt = $mysqli->prepare("SELECT Users.Username,Events.UserID, Events.GroupUsernames, Events.EventID,Events.EventTitle, Events.EventContent, Events.EventStartTime, Events.EventEndTime FROM Events join Users on Events.UserID = Users.UserID WHERE Events.UserID!=? AND ((EventEndTime IS NOT NULL
        AND EventEndTime > ?
        AND EventEndTime < ?)
       OR (EventStartTime > ?
           AND EventStartTime < ?))");

if (!$stmt) {
    echo json_encode(array(
        "success" => false,
        "message" => "sql prepare failed!" . htmlspecialchars($mysqli->error)
    ));
    exit;
}
$stmt->bind_param('issss', $userid,$d1, $d2, $d1, $d2);
if(!$stmt->execute()){
    echo json_encode(array(
        "success" => false,
        "message" => "sql execute failed!" . $mysqli->error
    ));
    $stmt->close();
    exit;
}
//$stmt->bind_result($count);
$stmt->bind_result($username_group_temp, $userid_group_temp, $group_temp, $event_id,$event_title,$event_content,$event_start_time,$event_end_time);
$rgx = "/\b".$username."\b/";

//print_r( [$username_group_temp, $userid_group_temp, $group_temp, $event_id,$event_title,$event_content,$event_start_time,$event_end_time] ) ;

//printf($stmt->fetch() == false);
//printf($stmt->error);
//printf($stmt->errno);

while($stmt->fetch()){
    if(preg_match($rgx,$group_temp)){
        if(!in_array($event_id,$eventIdArray)){
            $eventIdArray[] = htmlentities($event_id);
            $eventTitleArray[] = htmlentities($event_title);
            $eventContentArray[] = htmlentities($event_content);
            $eventStartTimeArray[] = htmlentities($event_start_time);
            $eventEndTimeArray[] = htmlentities($event_end_time);
            $eventTagArray[] = htmlentities("Group-event-by-".$username_group_temp);
            $eventGroupUsernames[] = htmlentities($group_temp);
        }
    }
}

$stmt->close();

echo json_encode(array(
    "success" => true,
    "EventID" => $eventIdArray,
    "EventTitle" => $eventTitleArray,
    "EventContent" => $eventContentArray,
    "EventStartTime" => $eventStartTimeArray,
    "EventEndTime" => $eventEndTimeArray,
    "Tag" => $eventTagArray,
    "GroupUsernames" => $eventGroupUsernames
));
exit;
?>
