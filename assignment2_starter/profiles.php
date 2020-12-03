<?php
require 'includes/functions.php';

session_start();
if (!isset($_SESSION['loggedFlag'])) {
    header('Location: index.php');
    exit();
}

$user = $_SESSION["username"];

if (count($_FILES) > 0) {
    if ($_FILES["picture"]["error"] > 0) {
        echo "<script>alert('Error!')</script>";
    } else if ($_FILES["picture"]["size"] > (4096 * 1024)) {
        echo "<script>alert('Picture size must be less than 4 megabytes!')</script>";
    } else if ($_FILES["picture"]["type"] != "image/jpeg" && $_FILES["picture"]["type"] != "image/jpg") {
        echo "<script>alert('Picture must be must be JPG format!')</script>";
    } else {
        $fileTempPath = $_FILES["picture"]["tmp_name"];
        $pictureName = "Image" . date('YmdHis', intval(time())) . '.jpg';
        $picturePath = "profiles/";
        if (checkDuplicateProfile($user)) {
            echo "<script>alert('Sorry, You already have a profile!')</script>";
        } else {
            if (move_uploaded_file($fileTempPath, $picturePath . $pictureName)) {
                if (addProfile($user, $pictureName)) {
                    header('Location: profiles.php');
                    exit();
                } else {
                    echo "<script>alert('Error storing to database!')</script>";
                }
            } else {
                echo "<script>alert('Error storing uploaded file!')</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">

    <div class="container">

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="login-panel text-center text-muted">
                    COMP 3015 Assignment 2
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <hr/>
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-comment"></i>
                    New Profile
                </button>
                <a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>
                <hr/>
            </div>
        </div>

        <div class="row">

            <?php
            $result = getAllProfiles();
            while ($row = $result->fetch_assoc()) { ?>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                        <span>
                        <?php echo $row["username"]; ?>
                        </span>
                            <span class="pull-right text-muted">
                            <a class="" href="<?php if($user == $row["username"]) echo "delete.php?id=".$row["id"]?>">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </span>
                        </div>
                        <div class="panel-body">
                            <p class="text-muted">
                            </p>
                            <img class="center-block"src="profiles/<?php echo $row["picture"] ?>" width="300" height="300" >
                        </div>
                        <div class="panel-footer">
                            <p></p>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>


    </div>
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form role="form" enctype="multipart/form-data" method="post" action="profiles.php">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">New Profile</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username</label>
                        <input class="form-control disabled" value="<?php echo $_SESSION['username'] ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <input class="form-control" type="file" name="picture">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Submit!"/>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
