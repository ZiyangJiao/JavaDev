<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>HJ News | My Reading List</title>
</head>

<body>
<?php
session_start();
require '../../user/database.php';

if (!isset($_SESSION['userid'])) {
    echo "<script> {window.alert('You need to log in before viewing your reading list. Click to log in.');location.href='login.php'} </script>";
    exit;
}

// If POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $reading_list_id = trim($_POST['reading_list_id']);

    // Delete reading list
    $stmt = $mysqli->prepare("delete from ReadingList where ReadingListID = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $reading_list_id);
    $stmt->execute();

    $stmt->close();

    echo "<script> {window.alert('Removed from reading list.');location.href='myreadinglist.php'} </script>";
    exit;
}

?>



    <div class="container">
        <a href=index.php class='btn btn-primary'><h1>HJ News</h1></a>
        <div class="row">
            <!-- My News list -->
            <div class="col-9">
                <h2>My Reading List</h2>
                <?php
                // Retrieve from MySQL
                $stmt = $mysqli->prepare("select a.StoryID, b.StoryTitle, c.SourceLink, a.ReadingListID from ReadingList a join Stories b on a.StoryID = b.StoryID join Sources c on b.StoryID = c.StoryID where a.UserID=? order by a.ReadingListID desc;");
                if (!$stmt) {
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $userid = $_SESSION['userid'];
                $stmt->bind_param('s', $userid);
                $stmt->execute();

                $stmt->bind_result($story_id, $story_title, $story_link, $reading_list_id);

                $news_counter = 1;
                $token = $_SESSION['token'];
                while ($stmt->fetch()) {
                    echo "
                        <div class='row'>
                            <div class='col-1'>$news_counter</div>
                            <div class='col-4'><a href=\"$story_link\" target='_blank'>$story_title</a></div>
                            <div class='col-4'>
                                <a href=\"newcomments.php?StoryID=$story_id\" target='_blank' class='btn btn-light'> View details/comments </a>
                            </div>
                            <div class='col-2'>
                                <form action='myreadinglist.php' method='POST'>    
                                    <input type='hidden' name='reading_list_id' value=$reading_list_id>
                                    <input type='hidden' name='token' value=$token>
                                    <input type='submit' value='Remove from my list' class='btn btn-danger'>
                                </form>
                            </div>
                        </div>";

                    ++$news_counter;
                }
                $stmt->close();

                if ($news_counter == 1) {
                    echo "<p>You have not added any news to reading list yet.</p>";
                    echo '<p><a href="index.php" class="btn btn-primary">Click to view news and add to your reading list</a></p>';
                }
                ?>
            </div>

            <!-- User info -->
            <div class="col">
                <?php
                $username = $_SESSION['username'];
                echo "<p>Logged in as: <mark>$username</mark></p>";
                echo '<p><a href="logout.php" class="btn btn-primary">Click to log out</a></p>';
                echo '<p><a href="index.php" class="btn btn-primary">← Back to all news</a></p>';
                echo '<p><a href="mynews.php" class="btn btn-info">View/Manage my news</a></p>';
                echo '<p><a href="mycomments.php" class="btn btn-info">View/Manage my comments</a></p>';
                ?>

            </div>
        </div>


    </div>
</body>

</html>