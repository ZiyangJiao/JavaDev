<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>My News | My News</title>
</head>

<body>
<?php
session_start();
require '../../user/database.php';

if (!isset($_SESSION['userid'])) {
    echo "<script> {window.alert('You need to log in before viewing your news. Click to log in.');location.href='login.php'} </script>";
    exit;
}

// If POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $story_id = trim($_POST['story_id']);

    // Delete Stories
    $stmt = $mysqli->prepare("delete from Stories where StoryID = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $story_id);
    $stmt->execute();

    // Delete Sources
    $stmt = $mysqli->prepare("delete from Sources where StoryID = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $story_id);
    $stmt->execute();

    // Delete Comments
    $stmt = $mysqli->prepare("delete from Comments where StoryID = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $story_id);
    $stmt->execute();

    // Delete Tags
    $stmt = $mysqli->prepare("delete from Tags where StoryID = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $story_id);
    $stmt->execute();

    $stmt->close();

    echo "<script> {window.alert('Story and its comments/links/tags now deleted.');location.href='mynews.php'} </script>";
    exit;
}

?>



    <div class="container">
        <a href=index.php class='btn btn-primary'><h1>HJ News</h1></a>
        <div class="row">
            <!-- My News list -->
            <div class="col-9">
                <h2>My news</h2>
                <?php
                // Retrieve news from MySQL
                $stmt = $mysqli->prepare("select a.StoryID, a.StoryTitle, b.SourceLink from Stories a join Sources b on a.StoryID = b.StoryID where UserID=? order by a.StoryID desc");
                if (!$stmt) {
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $userid = $_SESSION['userid'];
                $stmt->bind_param('s', $userid);
                $stmt->execute();

                $stmt->bind_result($story_id, $story_title, $story_link);

                $news_counter = 1;
                $token = $_SESSION['token'];
                while ($stmt->fetch()) {
                    echo "
                        <div class='row'>
                            <div class='col-1'>$news_counter</div>
                            <div class='col-8'><a href=\"$story_link\" target='_blank'>$story_title</a></div>
                            <div class='col-1'>
                            <a href=\"updatestories.php?StoryID=$story_id\" class='btn btn-warning'>Edit</a>
                            </div>
                            <div class='col-1'>
                                <form action='mynews.php' method='POST'>    
                                    <input type='hidden' name='story_id' value=$story_id>
                                    <input type='hidden' name='token' value=$token>
                                    <input type='submit' value='Delete' class='btn btn-danger'>
                                </form>
                            </div>
                        </div>
                        <p></p>";

                    ++$news_counter;
                }
                $stmt->close();

                if ($news_counter == 1) {
                    echo "<p>You have not added any news yet.</p>";
                    echo '<p><a href="addnews.php" class="btn btn-primary">Click to add your first news</a></p>';
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
                echo '<p><a href="mycomments.php" class="btn btn-info">View/Manage my comments</a></p>';
                echo '<p><a href="myreadinglist.php" class="btn btn-info">View/Manage my reading list</a></p>';
                ?>

            </div>
        </div>


    </div>
</body>

</html>