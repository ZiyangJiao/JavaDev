<?php
ini_set("session.cookie_httponly", 1);
session_start();
session_destroy();
//send exit status flag
echo json_encode(array(
    "success" => true
));
exit;
?>