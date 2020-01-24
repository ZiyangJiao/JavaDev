<?php
header("Content-Type: application/json");

$eventIdArray = [1,2,3];
$eventTitleArray = ["event1","event2","event3"];
$eventContentArray = ["event1","event2","event3"];
$eventStartTimeArray = ["2019-10-18 11:11:11","2019-10-18 11:11:11","2019-10-18 11:11:11"];
$eventEndTimeArray = ["2019-10-18 11:11:11","2019-10-18 11:11:11","2019-10-18 11:11:11"];
$eventTagArray[] = "Group-event-by-user2";
$eventTagArray[] = "Shared-by-user3";
$eventTagArray[] = null;
$eventGroupUsernames = ["user1","user2","user3"];

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

