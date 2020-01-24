<?php
session_start();
require '../../user/database.php';

// If POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $userid = $_SESSION['userid'];
    $story_id = trim($_POST['story_id']);

    // Add into reading list
    $stmt = $mysqli->prepare("insert into ReadingList (StoryID, UserID) values (?, ?);");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ss', $story_id, $userid);
    $stmt->execute();

    $stmt->close();

    // echo "<script> {window.alert('Comment now deleted.');location.href='mycomments.php'} </script>";
}

if (isset($_SESSION['userid'])) {
        $token = $_SESSION['token'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>HJ News | All news</title>
</head>

<body>
    <div class="container">
        <a href=index.php class='btn btn-primary'><h1>HJ News</h1></a>
        <div class="row">
            <!-- News list -->
            <div class="col-9">
                <?php if (isset($_SESSION['username'])) { ?>
                    <h2>News list <a href="addnewstory.php " class="btn btn-primary">+ Post news</a></h2>
                <?php } 
                else { ?>
                    <h2>News list </h2>
                <?php } ?>
                <?php
                // Retrieve news from MySQL
                if (isset($_SESSION['userid'])) {
                    $stmt = $mysqli->prepare("select a.StoryID, a.StoryTitle, b.SourceLink, c.ReadingListID from Stories a join Sources b on a.StoryID = b.StoryID left join ReadingList c on (b.StoryID = c.StoryID and c.UserID = ?) order by a.StoryID desc;");
                } else {
                    $stmt = $mysqli->prepare("select a.StoryID, a.StoryTitle, b.SourceLink, -1 as ReadingListID from Stories a join Sources b on a.StoryID = b.StoryID order by a.StoryID desc");
                }
                if (!$stmt) {
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                if (isset($_SESSION['userid'])) {
                    $userid = $_SESSION['userid'];
                    $stmt->bind_param('s', $userid);
                }
                $stmt->execute();

                $stmt->bind_result($story_id, $story_title, $story_link, $reading_list_id);

                ?>

                <table class="table table-striped">
                    <tbody>
                        <?php
                        $news_counter = 1;
                        echo "<tr>";
                        echo "<th scope='col'>No</th>";
                        echo "<th scope='col'>Title</th>";
                        echo "<th scope='col'>Details/Comments</th>";
                        if (isset($_SESSION['userid'])) {
                            echo "<th scope='col'>Reading list</th>";
                        }
                        echo "</tr>";
                        while ($stmt->fetch()) {
                            echo "<tr>";
                            echo "<td>$news_counter</td>";
                            echo "<td><a href= \"$story_link\" target='_blank'> $story_title </a></td>";
                            echo "<td><a href=\"newcomments.php?StoryID=$story_id\" target='_blank' class='btn btn-light'> View</a></td>";

                            //show menu if already login
                            if (isset($_SESSION['userid'])) {
                                if (is_null($reading_list_id)) {
                                    echo "<td><form action='index.php' method='POST'>    
                                    <input type='hidden' name='story_id' value=$story_id>
                                    <input type='hidden' name='token' value=$token>
                                    <input type='submit' value='+ Add' class='btn btn-light'>
                                    </form></td>";
                                } else {
                                    echo "<td><a class='btn btn-light'>✓</a></td>";
                                }
                            }

                            echo "</tr>";
                            ++$news_counter;
                        }
                        ?>
                    </tbody>
                </table>

                <?php
                $stmt->close();
                ?>
            </div>

            <!-- User info -->
            <div class="col">
                <?php
                // Show if logged in
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    echo "<p>Logged in as: <mark>$username</mark></p>";
                    echo '<p><a href="logout.php" class="btn btn-primary">Click to log out</a></p>';
                    echo '<p><a href="mynews.php" class="btn btn-info">View/Manage my news</a></p>';
                    echo '<p><a href="mycomments.php" class="btn btn-info">View/Manage my comments</a></p>';
                    echo '<p><a href="myreadinglist.php" class="btn btn-info">View/Manage my reading list</a></p>';
                } // Show if not logged in
                else {
                    echo "<p>Not logged in</p>";
                    echo '<p><a href="login.php" class="btn btn-primary">Click to login</a></p>';
                    echo "<p>If you would like to create an account</p>";
                    echo '<p><a href="register.php" class="btn btn-primary">Click to register</a></p>';
                }
                ?>

            </div>
        </div>
    </div>
</body>

</html>