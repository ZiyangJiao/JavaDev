<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>HJ News | Edit Comments</title>
</head>

<body>
<?php
session_start();
require '../../user/database.php';

//verify login status
if (!isset($_SESSION['userid'])) {
    echo "<script> {window.alert('You need to log in before editing commments posted by you. Click to log in.');location.href='login.php'} </script>";
    exit;
}

$token = $_SESSION['token'];
$userid = $_SESSION['userid'];


// If POST -- Ready to process updated comment info
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!hash_equals($_SESSION['token'], $_POST['token'])) {
            die("Request forgery detected");
        }
    }

    $story_id = $_POST['story_id'];
    $comment_id = $mysqli->real_escape_string($_POST['comment_id']);

    $newcomment = $mysqli->real_escape_string($_POST['new_comment']);

    //validate new comments
    if (empty($newcomment)) {
        echo "<script> {window.alert('Please type something in the comment content. You also have the option to click Delete button to delete this comment.');location.href='mycomments.php'} </script>";
        exit;
    } else {
        //update new comment into database
        $mysqli->query("UPDATE Comments SET CommentContent='" . $newcomment . "'" . "WHERE CommentID='" . $comment_id . "'");
        echo "<script> {window.alert('Edit succeeded. Click to view the result');location.href='updatecomments.php?StoryID=$story_id&CommentID=$comment_id'} </script>";
        exit;
    }
}


// If GET -- ready to display details of the choosed comment
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $StoryID = $mysqli->real_escape_string($_GET['StoryID']);
    $CommentID = $mysqli->real_escape_string($_GET['CommentID']);
    $res_stories = $mysqli->query("SELECT StoryTitle,StoryContent FROM Stories WHERE StoryID='" . $StoryID . "'");
    $res_comments = $mysqli->query("SELECT CommentContent FROM Comments WHERE CommentID='" . $CommentID . "'");

    if ($res_comments->num_rows == 1) {
        $row_stories = $res_stories->fetch_assoc();
        $row_comments = $res_comments->fetch_assoc();
    } else {
        echo "<script> {window.alert('Comment already deleted. Click to view comments by you');location.href='mycomments.php'} </script>";
        exit;
    }
}


?>

    <div class="container">
        <a href=index.php class='btn btn-primary'>
            <h1>HJ News</h1>
        </a>
        <div class="row">
            <div class="col-9">
                <h2>Edit comments</h2>
                <h3><mark>Edit current comment and click to confirm</mark></h3>
                <!--Form used to receive updated info of the choosed comment-->
                <form action="updatecomments.php?StoryID=<?php echo $StoryID ?>&CommentID=<?php echo $CommentID ?>" method="POST">
                    <div class="form-group">
                        <!--<label>-->
                            <h4>Comment content</h4>
                        <!--</label>-->
                        <textarea name="new_comment" class="form-control" rows=5><?php echo "$row_comments[CommentContent]"; ?></textarea>
                    </div>
                    <div class="form-group">
                        <input type='hidden' name='token' value="<?php echo $token ?>">
                        <input type='hidden' name='story_id' value="<?php echo $StoryID ?>">
                        <input type='hidden' name='comment_id' value="<?php echo $CommentID ?>">
                    </div>

                    <button type="submit" class="btn btn-warning">Confirm edit</button>
                </form>

                <hr>

                <!-- To delete  -->
                <form action='mycomments.php' method='POST'>
                    <input type='hidden' name='comment_id' value="<?php echo $CommentID?>">
                    <input type='hidden' name='token' value="<?php echo $token?>">
                    <input type='submit' value='Delete this comment' class='btn btn-danger'>
                </form>
                <br>
                <!-- To cancel  -->
                <a href=mycomments.php class="btn btn-secondary">Cancel | Back to my comments</a>
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
                    echo "<p>Not logged in</p>";
                    echo '<p><a href="login.php" class="btn btn-primary">Click to login</a></p>';
                    echo "<p>if you would like to create an account</p>";
                    echo '<p><a href="register.php" class="btn btn-primary">Click to register</a></p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>