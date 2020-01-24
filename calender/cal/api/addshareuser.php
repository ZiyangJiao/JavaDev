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
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];

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
//$username = "user";

// check the shared user name

if($json_obj['shareuser'] == null){
    $shareuser_filter = null;
} elseif(!empty($shareuser)) {
    $shareuser_filter = "";
    foreach ($shareuser as $name) {
        if (empty($name) ||  $name == $username) {
            $shareuser_filter = $shareuser_filter . $name . ",";
            continue;
        }
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM Users WHERE Username=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        // Bind the parameter
        $stmt->bind_param('s', $name);
        $stmt->execute();

        // Bind the results
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count != 1) {
            //user already exist
            $shareuser_filter = "";
            echo json_encode(array(
                "success" => false,
                "message" => "Shared username error: " . $name
            ));
            exit;
        }

        $shareuser_filter = $shareuser_filter . $name . ",";
    }
}

/*
//find my events and update shared group info
if(!empty($shareuser_filter)){
    $stmt = $mysqli->prepare("SELECT EventID FROM Events WHERE UserID=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Bind the parameter
    $stmt->bind_param('i', $userid);
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($event);
    //update shared user
    while($stmt->fetch()){
        $insertshareuser = $mysqli->prepare("UPDATE Events SET GroupUsernames=CONCAT(Events.GroupUsernames,$shareuser_filter) WHERE EventID=?");
        if (!$insertshareuser) {
            echo json_encode(array(
                "success" => false,
                "message" => "sql prepare failed!"
            ));
            exit;
        }
        $insertshareuser->bind_param('i', $event);
        $insertshareuser->execute();
        $insertshareuser->close();
    }
    $stmt->close();
    echo json_encode(array(
        "success" => true
    ));
    exit;
}
*/

//update share user info
if(!($shareuser_filter == null)) {
    //echo 'shareuser_filter != 0';
    $insertshareuser = $mysqli->prepare("UPDATE Users SET ShareUsernames = ? WHERE UserID=?");
    if (!$insertshareuser) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    // Bind the parameter
    $insertshareuser->bind_param('si', $shareuser_filter,$userid);
    if (!$insertshareuser->execute()) {
        echo json_encode(array(
            "success" => false,
            "message" => "sql execute failed!" . $insertshareuser->error
        ));
        $insertshareuser->close();
        exit;
    } else {
        echo json_encode(array(
            "success" => true
        ));
        $insertshareuser->close();
        exit;
    }
}else{
    $insertshareuser = $mysqli->prepare("UPDATE Users SET ShareUsernames = ? WHERE UserID=?");
    if (!$insertshareuser) {
        printf("Query Prep Failed: %s\n", $insertshareuser->error);
        exit;
    }
    // Bind the parameter
    $insertshareuser->bind_param('si', $shareuser_filter,$userid);
    if($insertshareuser->execute()){
        echo json_encode(array(
            "success" => true,
            "message" => "Reset share user success"
        ));
    }else {
        echo json_encode(array(
            "success" => false,
            "message" => "Reset share user fail"
        ));
    }
    exit();
}
?>