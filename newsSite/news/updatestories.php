<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>HJ News | Edit News</title>
</head>

<body>
<?php
session_start();
require '../../user/database.php';

//verify login status
if (!isset($_SESSION['userid'])) {
    echo "<script> {window.alert('You need to log in before editing news posted by you. Click to log in.');location.href='login.php'} </script>";
    exit;
}

$token = $_SESSION['token'];
$userid = $_SESSION['userid'];


// If POST -- ready to update story's info
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!hash_equals($_SESSION['token'], $_POST['token'])) {
            die("Request forgery detected");
        }
    }

    $newtitle = $_POST['newtitle'];
    $newlink = $_POST['newlink'];
    $newcontent = $_POST['newcontent'];
    $StoryID = $mysqli->real_escape_string($_POST['StoryID']);

    //validate new title and link
    if (empty($newtitle) || empty($newlink)) {
        echo "<script> {window.alert('Please fill at least news title and source link. Thanks.');location.href='updatestories.php?StoryID=$StoryID'} </script>";
        exit;
    } else {
        //update stories' info
        $newtitle = $mysqli->real_escape_string($_POST['newtitle']);
        $newlink = $mysqli->real_escape_string($_POST['newlink']);
        $newcontent = $mysqli->real_escape_string($_POST['newcontent']);
        $mysqli->query("UPDATE Stories SET StoryTitle='" . $newtitle . "'" . "WHERE StoryID='" . $StoryID . "'");
        $mysqli->query("UPDATE Stories SET StoryContent='" . $newcontent . "'" . "WHERE StoryID='" . $StoryID . "'");
        $mysqli->query("UPDATE Sources SET SourceLink='" . $newlink . "'" . "WHERE StoryID='" . $StoryID . "'");
        echo "<script> {window.alert('Edit succeeded. Click to view the result');location.href='newcomments.php?StoryID=$StoryID'} </script>";
        exit;
    }
}

// If GET -- display the choosed story's info
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $StoryID = $mysqli->real_escape_string($_GET['StoryID']);
}
$res_stories = $mysqli->query("SELECT StoryTitle,StoryContent FROM Stories WHERE StoryID=" . $StoryID . " and UserID ='" . $userid . "'");
$res_sources = $mysqli->query("SELECT SourceLink FROM Sources WHERE StoryID='" . $StoryID . "'");

if ($res_stories->num_rows == 1) {
    $row_stories = $res_stories->fetch_assoc();
    $row_sources = $res_sources->fetch_assoc();
} else {
    echo "<script> {window.alert('News already deleted or you do not have the permission to edit this news. Please view other news posted by you');location.href='mynews.php'} </script>";
    exit;
}

?>



    <div class="container">
        <a href=index.php class='btn btn-primary'>
            <h1>HJ News</h1>
        </a>
        <div class="row">
            <div class="col-9">
                <h2>Edit news</h2>
                <h3><mark>Edit current news title/link/content and click to confirm</mark></h3>
                <form action="updatestories.php" method="POST">
                <!--Form used to receive story's updated info-->
                    <div class="form-group">
                        <!--<label>-->
                            <h4>News title</h4>
                        <!--</label>-->
                        <input type="text" name="newtitle" class="form-control" value="<?php echo $row_stories['StoryTitle'] ?>">
                    </div>
                    <div class="form-group">
                        <!--<label>-->
                            <h4>Source link</h4>
                        <!--</label>-->
                        <input type="text" name="newlink" class="form-control" value="<?php echo $row_sources['SourceLink'] ?>">
                    </div>
                    <div class="form-group">
                        <!--<label>-->
                            <h4>Content</h4>
                        <!--</label>-->
                        <!--<input type="text" name="content" class="form-control" placeholder="Content">-->
                        <textarea name="newcontent" class="form-control" rows=5><?php echo $row_stories['StoryContent'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <input type='hidden' name='token' value="<?php echo $token ?>">
                        <input type='hidden' name='StoryID' value="<?php echo $StoryID ?>">
                    </div>

                    <button type="submit" class="btn btn-warning">Confirm edit</button>
                </form>

                <hr>

                <!-- To delete  -->
                <form action='mynews.php' method='POST'>
                    <input type='hidden' name='story_id' value=<?php echo $StoryID?>>
                    <input type='hidden' name='token' value=<?php echo $token?>>
                    <input type='submit' value='Delete this news' class='btn btn-danger'>
                </form>
                <br>
                <!-- To cancel  -->
                <a href=mynews.php class="btn btn-secondary">Cancel | Back to my news</a>
            </div>

            <!-- User info -->
            <div class="col">
                <?php
                // Show if logged in
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    echo "<p>Logged in as: <mark>$username</mark> </p>";
                    echo '<p><a href="logout.php" class="btn btn-primary">Click to log out</a></p>';
                    echo '<p><a href="index.php" class="btn btn-primary">← Back to all news</a></p>';
                    echo '<p><a href="mynews.php" class="btn btn-info">View/Manage my news</a></p>';
                    echo '<p><a href="mycomments.php" class="btn btn-info">View/Manage my comments</a></p>';
                    echo '<p><a href="myreadinglist.php" class="btn btn-info">View/Manage my reading list</a></p>';
                } // Show if not logged in
                else {
                    echo "<p><Not logged in</p>";
                    echo '<p><a href="login.php" class="btn btn-primary">Click to login</a></p>';
                    echo "<p><div>If you would like to create an account</div></p>";
                    echo '<p><a href="register.php" class="btn btn-primary">Click to register</a></p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>