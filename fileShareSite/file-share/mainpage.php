<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo "$_SESSION[username]" ?>'s mainpage</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
    <?php
    if(!isset($_SESSION['username'])){
        echo "<script> {window.alert('Cannot view before login. Click to login.');location.href='login.php'} </script>";
        exit;
    }
    $username = $_SESSION['username'];
    echo "Current user: $username";
    ?>
    <br>

    Click <a href="logout.php">here</a> to logout
    <hr>

    <!-- Upload -->
    <h1>Upload a new file</h1>
    
        <form enctype="multipart/form-data" action="upload.php" method="POST">
            <p>
                <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
                <label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />
            </p>
            <p>
                <input type="submit" value="Upload" />
            </p>
        </form>
    

    <hr>

    <h1>Your existing files</h1>

    <?php
    $fileSource = "../../user/" . $username . "/";
    // Show all files under user's folder
    if ((isset($_GET['sort'])) && ($_GET['sort'] == "descend")) {
        $fileList = scandir($fileSource, 1);  // descend
    } else {
        $fileList = scandir($fileSource);  // ascend (default)
    }
    ?>

    <!-- Change sort mode -->
    You can choose to sort files by <b>filenames</b> in
    <a href="mainpage.php?sort=ascend"><b>ascending</b></a>
    or
    <a href="mainpage.php?sort=descend"><b>descending</b></a>
    order
    <br>
    <br>

    <!-- Table to show all files -->
    <table>
        <thead>
            <tr>
                <th>Filename</th>
                <th>Uploaded time</th>
                <th>Click to view</th>
                <th>Click to delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($fileList as $i) {
                if (($i != ".") && ($i != "..")) {
                    echo "<tr>";
                    echo "<td>$i</td>";
                    // Display date and time in local time zone
                    date_default_timezone_set("America/Chicago");
                    $fileUpdatedTime = date("d M Y H:i:s", filemtime($fileSource . $i));
                    echo "<td>$fileUpdatedTime</td>";
                    $fileHref = 'viewDetails.php?filename=' . $i;
                    $deleteHref = 'deleteFile.php?filename=' . $i;
                    $target = "_blank";
                    echo "<td><a href=\"$fileHref\" target=$target>View</a></td>";
                    echo "<td><a href=\"$deleteHref\">Delete</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <hr>

    <!-- Link to rename -->
    <h1>Rename an existing file</h1>
    Click <a href="renameFile.php">here</a> to rename an existing file.

</body>

</html>