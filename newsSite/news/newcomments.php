<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>HJ News | News Details</title>
</head>

<body>
<?php
session_start();
require '../../user/database.php';

$StoryID = $_GET['StoryID'];
//$cha = "SELECT StoryTitle FROM Stories WHERE StoryID ='".$StoryID."'";
//$stmt = $mysqli->prepare($cha);
//if(!$stmt){
    //    printf("Query Prep Failed: %s\n", $mysqli->error);
    //    exit;
    //}
    //$stmt->execute();
    //$res_stories = $stmt->get_result();
    

// If POST --- update new comments
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //verify form token
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $newcomments = $mysqli->real_escape_string($_POST['new_comments']);

    //validate new comments
    if (empty($newcomments)) {
        echo "<script> {window.alert('Please write something before submit');location.href='newcomments.php?StoryID=$StoryID'} </script>";
        exit;
    } else {
        //insert new comment into database
        $stmt = $mysqli->prepare("insert into Comments (UserID, StoryID, CommentContent) values (?, ?, ?)");
        if (!$stmt) {
            printf("Insert Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('sss', $_SESSION['userid'], $StoryID, $newcomments);
        $stmt->execute();
        $stmt->close();
        // echo "<script> {window.alert('add success!');location.href='newcomments.php?StoryID=".$StoryID."} </script>";
    }
}

//retrive new's comments from databse
// SQL injection escape | $mysqli->real_escape_string
$StoryID = $mysqli->real_escape_string($StoryID);
$res_stories = $mysqli->query("SELECT StoryTitle,StoryContent FROM Stories WHERE StoryID ='" . $StoryID . "'");
$res_sources = $mysqli->query("SELECT SourceLink FROM Sources WHERE StoryID='" . $StoryID . "'");
$res_comments = $mysqli->query("SELECT a.CommentContent, a.InsertTime, b.Username FROM Comments a join Users b on a.UserID = b.UserID WHERE a.StoryID='" . $StoryID . "'");

$row_stories = $res_stories->fetch_assoc();
$row_sources = $res_sources->fetch_assoc();

// switch to next new's comment details
$res_stories_next = $mysqli->query("SELECT StoryTitle, StoryID, StoryContent FROM Stories WHERE StoryID >'" . $StoryID . " limit 1'");

if ($res_stories->num_rows == 1) {
} else {
    echo "<script> {window.alert('News already deleted. Please view other news');location.href='index.php'} </script>";
    exit;
}

?>




    <div class="container">
        <a href=index.php class='btn btn-primary'><h1>HJ News</h1></a>
        <div class="row">
            <div class="col-9">
                <!--Display details of the choosed news-->
                <h2><?php echo "$row_stories[StoryTitle]" ?></h2>
                <p>Source: <a href="<?php echo "$row_sources[SourceLink]" ?>" target="_blank"><?php echo "$row_sources[SourceLink]" ?></a></p>
                <p><?php echo "$row_stories[StoryContent]"; ?></p>

                <!-- Share button from AddThis.com -->
                <div class="addthis_inline_share_toolbox">
                    <script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5d843c83ee03f189"></script>
                </div>

                <hr>

                <h3>Next news</h3>
                <?php
                if ($row_stories_next = $res_stories_next->fetch_assoc()) {
                    echo "<h4>$row_stories_next[StoryTitle]</h4>";
                    echo "<a href=newcomments.php?StoryID=$row_stories_next[StoryID] class='btn btn-primary'>Click to view</a>";
                }
                else {
                    echo "<h4>This is the last news</h4>";
                    echo '<p><a href="index.php" class="btn btn-primary">← Back to all news</a></p>';
                }
                ?>

                <hr>

                <!--Display comments of the choosed news-->
                <h2>Comments</h2>
                <?php
                echo "<ul>\n";
                while ($row_comments = $res_comments->fetch_assoc()) {
                    printf(
                        "\t<li>%s | %s wrote: %s</li>\n",
                        htmlspecialchars($row_comments["InsertTime"]),
                        htmlspecialchars($row_comments["Username"]),
                        htmlspecialchars($row_comments["CommentContent"])
                    );
                }
                echo "</ul>\n";
                ?>

                <hr>

                <h2>Add my comment</h2>
                <!-- Show if logged in -->
                <?php if (isset($_SESSION['username'])) {?>
                    <?php $token = $_SESSION['token'];?>
                    <form name="newcomments" action="newcomments.php?StoryID=<?php echo "$StoryID" ?>" method="POST">
                        <div class="form-group">
                            <input type='hidden' name='token' value="<?php echo $token ?>">
                            <input type='hidden' name='StoryID' value="<?php echo $StoryID ?>">
                            <textarea name="new_comments" placeholder="Add your new comment here" rows="5" cols="40"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                <!-- Show if not logged in -->
                <?php }
                else {
                    echo "<p>If you would like to leave a comment, please log in.</p>";
                    echo '<p><a href="login.php" class="btn btn-primary">Click to login</a></p>';
                }
                ?>
            </div>

            <!-- User info -->
            <div class="col">
                <?php
                // Show other opreations and webpage switch if logged in 
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    echo "<p>Logged in as: <mark>$username</mark></p>";
                    echo '<p><a href="logout.php" class="btn btn-primary">Click to log out</a></p>';
                    echo '<p><a href="index.php" class="btn btn-primary">← Back to all news</a></p>';
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
