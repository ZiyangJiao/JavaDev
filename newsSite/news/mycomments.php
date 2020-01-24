<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>HJ News | My Comments</title>
</head>

<body>
<?php
session_start();
require '../../user/database.php';

if (!isset($_SESSION['userid'])) {
    echo "<script> {window.alert('You need to log in before viewing your comments. Click to log in.');location.href='login.php'} </script>";
    exit;
}

// If POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $comment_id = trim($_POST['comment_id']);

    // Delete Comments
    $stmt = $mysqli->prepare("delete from Comments where CommentID = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $comment_id);
    $stmt->execute();

    $stmt->close();

    echo "<script> {window.alert('Comment now deleted.');location.href='mycomments.php'} </script>";
    exit;
}

?>



    <div class="container">
        <a href=index.php class='btn btn-primary'><h1>HJ News</h1></a>
        <div class="row">
            <!-- My Comments list -->
            <div class="col-9">
                <h2>My comments</h2>
                <?php
                // Retrieve comments from MySQL
                $stmt = $mysqli->prepare("select a.StoryID, a.StoryTitle, b.SourceLink, c.CommentID, c.CommentContent from Stories a join Sources b on a.StoryID = b.StoryID join Comments c on b.StoryID = c.StoryID where c.UserID=? order by a.StoryID desc;");
                if (!$stmt) {
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $userid = $_SESSION['userid'];
                $stmt->bind_param('s', $userid);
                $stmt->execute();

                $stmt->bind_result($story_id, $story_title, $story_link, $comment_id, $comment_content);

                $comment_counter = 1;
                $token = $_SESSION['token'];
                while ($stmt->fetch()) {
                    echo "
                        <div class='row'>
                            <div class='col-1'>$comment_counter</div>
                            <div class='col-4'><a href=$story_link target='_blank'>$story_title</a></div>
                            <div class='col-4'>$comment_content</div>
                            <div class='col-1'>
                                <a href=\"updatecomments.php?StoryID=$story_id&CommentID=$comment_id\" class='btn btn-warning'>Edit</a>
                            </div>
                            <div class='col-1'>
                                <form action='mycomments.php' method='POST'>    
                                    <input type='hidden' name='comment_id' value=$comment_id>
                                    <input type='hidden' name='token' value=$token>
                                    <input type='submit' value='Delete' class='btn btn-danger'>
                                </form>
                            </div>
                        </div>";

                    ++$comment_counter;
                }
                $stmt->close();

                if ($comment_counter == 1) {
                    echo "<p>You have not made any comments yet.</p>";
                    echo '<p><a href="index.php" class="btn btn-primary">Click to view news and make comments</a></p>';
                }
                ?>
            </div>

            <!-- User info -->
            <div class="col">
                <?php
                $username = $_SESSION['username'];
                echo "<p> Logged in as: <mark>$username</mark> </p>";
                echo '<p><a href="logout.php" class="btn btn-primary">Click to log out</a></p>';
                echo '<p><a href="index.php" class="btn btn-primary">← Back to all news</a></p>';
                echo '<p><a href="mynews.php" class="btn btn-info">View/Manage my news</a></p>';
                echo '<p><a href="myreadinglist.php" class="btn btn-info">View/Manage my reading list</a></p>';
                ?>

            </div>
        </div>


    </div>
</body>

</html>