<?php
session_start();
$filename = $_GET['filename'];
$full_path = sprintf("../../user/%s/%s", $_SESSION['username'], $filename);
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($full_path);
// edge case for empty files
if ($mime == "inode/x-empty") {
    $mime = "text/plain";
}
// Finally, set the Content-Type header to the MIME type of the file, and display the file.
header("Content-Type:" . $mime);
readfile($full_path);
