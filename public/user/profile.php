<?php

require_once("settings/settings.php");
require_once("components/user/authentication.php");
require_once("components/user/getInfo.php");

require_once("utils/user/currentUser.php");

// how this code blocks works:
// (1) At first, it checks if the request to server is "get" or not, if not "get", then invalid
// (2) then, it checks if a 'username' is provided in the 'url', the format is as follows:
//     url format: https://kuhu.com/u/profile.php?u=xyz
// (3) if username is not provided in url, then see if the user is logged in
// (4) if the user is logged in, then show his/her profile
// (5) if the user is not logged in, show "please, login to see your profile"
// (6) if the user name is provided, then show his/her profile
// (7) otherwise, a username is provided in the url: search for the username
// (8) if returned result i.e. $info is an string, then it's an error message
// (9) else, retrive user info and show in the profile page

if($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo "invalid request";
    exit(1);
}

// search profile by username
// url format: https://kuhu.com/u/profile.php?u=xyz

$firstName = "";
$lastName = "";
$username = "";
$email = "";

if(!isset($_GET["user"])) {
    if(!isAuthenticated()) {
        echo "please, login to see your profile";
        exit(0);
    } else {
        $user = getCurrentUsername();
    }
} else {
    $user = $_GET["user"];
}


$info = getInfo($user);

// check if info is an error
// info should be an array object
// but it would be an string if error occurs
if(gettype($info) === "string") {
    echo "<p>" . $info . "</p>";
    exit(0);
} else {
    $username = $info["username"];
    $firstName = $info["firstName"];
    $lastName = $info["lastName"];
    $email = $info["email"];
}

// making the first characters uppercase
$firstName[0] = strtoupper($firstName[0]);
$lastName[0] = strtoupper($lastName[0]);

$liveRoot = Settings::getLiveRoot();
?>


<!DOCTYPE html>
<html>
<head>
    <title>
        <?php
            if(isset($username)):
                echo $firstName . " " . $lastName;
            else:
                echo "Profile";
            endif;
        ?>
        - Kuhu
    </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="<?= $liveRoot;?>/assets/css/kuhu.css">
</head>

<body>
    <div class="app-global-container --kuhu-bg-app-global --kuhu-min-height-viewport">
        
        <?php
            include("templates/general/topbar/topbarUtility.php");
        ?>

        <div class="--kuhu-container --kuhu-margin-x-auto --kuhu-padding-x-lg-12px">
            <div class="--kuhu-row">
                <div class="--kuhu-col-md-65 --kuhu-col-lg-72vw --kuhu-min-height-viewport">
                    <?php
                        include("templates/user/profile/profileWallUtility.php");
                    ?>

                    <div class="--kuhu-container">
                        <div class="--kuhu-row">
                            <div class="--kuhu-col-lg-20vw">
                                <!-- intro utility -->
                                <?php
                                    include("templates/user/profile/introUtility.php");
                                ?>
                            </div>
                            <div class="--kuhu-col-lg-50vw --kuhu-margin-left-auto">
                                <!-- profile Bar Utility -->
                                <?php
                                    include("templates/user/profile/profileBarUtility.php");
                                ?>
                                <!-- profile services -->
                                <?php
                                    include("templates/post/postUtility.php");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="--kuhu-display-none --kuhu-display-md-block --kuhu-col-md-32 --kuhu-col-lg-24vw --kuhu-margin-left-auto --kuhu-margin-top-12px">
                    <?php
                        include("templates/universe/topTrending/topTrendingUtility.php");
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="app-cell-templates --kuhu-display-none">
        <?php
            include("templates/general/cellTemplates.php");
        ?>
    </div>
    
    <script type="text/javascript" src="<?= $liveRoot; ?>/assets/js/index.js"></script>
    
</body>
</html>