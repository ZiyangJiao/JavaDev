<?php

$sql_event = "
SELECT EventID,
       EventTitle,
       EventContent,
       EventStartTime,
       EventEndTime,
       Tag,
       GroupUsernames
FROM Events
WHERE UserID = ?
  AND ((EventEndTime IS NOT NULL
        AND EventEndTime > ?
        AND EventEndTime < ?)
       OR (EventStartTime > ?
           AND EventStartTime < ?));
";

?>
