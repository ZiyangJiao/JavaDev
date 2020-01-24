<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Using Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>HJ News | Post news</title>
</head>
<body>
<?php
session_start();
require '../../user/database.php';

//verify login status
if (!isset($_SESSION['userid'])) {
    echo "<script> {window.alert('You need to log in before posting news. Click to log in.');location.href='login.php'} </script>";
    exit;
}

// If POST -- ready to insert new story to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }

    $newtitle = $_POST['title'];
    $newlink = $_POST['url'];
    $newcontent = $_POST['content'];


    //validate news
    if (empty($newtitle) || empty($newlink)) {
        echo "<script> {window.alert('Please fill at least news title and source link. Thanks.');location.href='addnewstory.php'} </script>";
        exit;
    } else {
        //add new story into database
        $stmt = $mysqli->prepare("insert into Stories (UserID,StoryTitle,StoryContent) values ( ? ,?, ?)");
        //if(!$stmt){
        //    printf("Insert Prep Failed: %s\n", $mysqli->error);
        //    exit;
        //}

        $stmt->bind_param('sss', $_SESSION['userid'], $newtitle, $newcontent);
        $stmt->execute();
        $stmt->close();
        //add new url into database
        //$res_stories = $mysqli->query("SELECT StoryID FROM Stories WHERE StoryTitle='".$newtitle."'"."AND StoryContent='".$newcontent."'");
        //if($res_stories->num_rows==1){
        //    $row_stories = $res_stories->fetch_assoc();
        //}else{
        //    echo "<script> {window.alert('Invalid input!');location.href='addnewstory.php'} </script>";
        //    exit;
        //}
        
        //get the StoryID of the new inserted news
        $stmt = $mysqli->prepare("select StoryID from Stories where StoryTitle=? and StoryContent=?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ss', $newtitle, $newcontent);

        $stmt->execute();

        $stmt->bind_result($storyid);
        $stmt->fetch();
        $stmt->close();
        
        //inset link souce in to database
        $stmt = $mysqli->prepare("insert into Sources (StoryID,SourceLink) values (?,?)");
        if (!$stmt) {
            printf("Insert Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ss', $storyid, $newlink);
        $stmt->execute();
        $stmt->close();
        echo "<script> {window.alert('News added. Click to see it on news page');location.href='index.php'} </script>";
        exit;
    }
}

$token = $_SESSION['token'];

?>




    <div class="container">
        <a href=index.php class='btn btn-primary'>
            <h1>HJ News</h1>
        </a>
        <div class="row">
            <div class="col-9">
                <h2>Post news</h2>
                <hr>
                <!--Form used to receive new stroy's info-->
                <form name="addnewstory" action="addnewstory.php" method="POST">
                    <div class="form-group">
                        <h4>News title</h4>
                        <input type="text" name="title" class="form-control" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <h4>Source link</h4>
                        <input type="text" name="url" class="form-control" placeholder="For example, https://bbc.com/news/headline">
                    </div>
                    <div class="form-group">
                        <h4>Content</h4>
                        <!--<input type="text" name="content" class="form-control" placeholder="Content">-->
                        <textarea name="content" class="form-control" rows=5 placeholder="(Optional) You can put a brief introduction of news here"></textarea>
                    </div>
                    <div class="form-group">
                        <input type='hidden' name='token' value="<?php echo "$token" ?>" >
                    </div>

                    <button type="submit" class="btn btn-primary">Post this news</button>
                </form>
            </div>

            <!-- User info -->
            <div class="col">
                <?php
                $username = $_SESSION['username'];
                echo "<p> Logged in as: <mark>$username</mark> </p>";
                echo '<p><a href="logout.php" class="btn btn-primary">Click to log out</a></p>';
                echo '<p><a href="index.php" class="btn btn-primary">← Back to all news</a></p>';
                echo '<p><a href="mynews.php" class="btn btn-info">View/Manage my news</a></p>';
                echo '<p><a href="mycomments.php" class="btn btn-info">View/Manage my comments</a></p>';
                echo '<p><a href="myreadinglist.php" class="btn btn-info">View/Manage my reading list</a></p>';
                ?>

            </div>
        </div>
     </div>
</body>

</html>