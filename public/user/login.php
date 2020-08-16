<?php
/**
 * check if user already logged in or not
 * if logged in
 * then, redirect him/her to index page
 */

require_once("settings/settings.php");
require_once("utils/time/time.php");

require_once("components/user/filter.php");

require_once("components/user/authentication.php");


$liveRoot = Settings::getLiveRoot();

if(isAuthenticated()) {
	header("Location: $liveRoot/index.php");
	exit();
}

$username = "";
$password = "";
$errorMsg = "";

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$username = $_POST["username"];
	$password = $_POST["password"];

    $username = filterUsername($username);
    $authenticationStatus = authenticate($username, $password);
    if($authenticationStatus === true) {
        header("Location: $liveRoot/index.php");
        exit();
        // the following commented lines are for testing time features and time functions
        // // note: my pc by default provide utc time, even if you set some time zone like +6 dhaka
        // //       so, the time retrieved by date() function or $_SERVER["REQUEST_TIME"] timestamp
        // //       the time always gonna be for +0 utc i.e. utc standard time with +0
        // //       so, to get the local time zone you need to add localtime
        // echo "<p>" . "loggedin at: " . $_SERVER["REQUEST_TIME"] . "</p>";
        // echo "<p>" . "now(using strtotime): " . strtotime("now") . "</p>";
        // echo "<p>" . "mysql format date(req time): " . date("Y-m-d H:i:s", $_SERVER["REQUEST_TIME"]) . "</p>";
        // echo "<p>" . "mysql format date(pc time): " . date("Y-m-d H:i:s") . "</p>";
        
        // // adding additional time for dhaka zone
        // $dhakaZone = strtotime("+6 hour");
        // echo "<p>" . "mysql format date(pc time, dhaka zone): " . date("Y-m-d H:i:s", $dhakaZone) . "</p>";
        // echo "<p>" . "mysql format date(req time, dhaka zone, settings/time.php): " . Time::bangladeshDateTime() . "</p>";
        // exit();

        // register_time and last_login testing
        
    } else {
        // echo "<p>" . $authenticationStatus . "</p>";
        // exit(1);
        $errorMsg = $authenticationStatus;
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Sign in</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link type="text/css" rel="stylesheet" href="<?= $liveRoot;?>/assets/css/style.css"> -->
    <link type="text/css" rel="stylesheet" href="<?= $liveRoot;?>/assets/css/kuhu.css">
</head>

<body>
    <div class="app-global-container --kuhu-min-height-viewport --kuhu-bg-app-global">
        <div class="--kuhu-top-bar-index-page --kuhu-box --kuhu-margin-bottom-lg-12px">
            <!-- index page top bar has a margin of 12px in bottom, that's why we need to paste topbar utility inside a div -->
            <!-- if you observe page like user/profile.php or universe/view.php, you'll see that topbar utility is pasted directly -->
            <?php
                include("templates/general/topbar/topbarUtility.php");
            ?>
        </div>

        <div class="--kuhu-box --kuhu-margin-top-64px --kuhu-margin-x-auto --kuhu-padding-bottom-64px --kuhu-max-width-400px">
            <div id="login-form-wrapper" class="--kuhu-box --kuhu-padding-x-32px">
                <div id="login-form-prefix" class="--kuhu-margin-bottom-32px --kuhu-font-28px --kuhu-font-700">
                    Log In to Kuhu
                </div>
                
                <form id="login-form" action="" method="POST">
                    <div id="login-form-item-container">

                        <div id="login-form-username-box" class="login-form-item-box --kuhu-margin-top-16px --kuhu-font-13px --kuhu-font-700 --kuhu-color-hx-666">
                            <div id="login-form-username-prefix" class="login-form-item-prefix --kuhu-margin-bottom-6px">
                                <label for="login-form-username">Username</label>
                            </div>
                            
                            <div class="login-form-item">
                                <input type="text" name="username" id="login-form-username" value="<?= $username?>" required class="--kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-round-2px --kuhu-border-auth-form --kuhu-font-16px --kuhu-font-600">
                            </div>
                        </div>
                        
                        <div id="login-form-password-box" class="login-form-item-box --kuhu-margin-top-16px --kuhu-font-13px --kuhu-font-700 --kuhu-color-hx-666">
                            <div id="login-form-password-prefix" class="login-form-item-prefix --kuhu-margin-bottom-6px">
                                <label for="login-form-password-prefix">Password</label>
                            </div>
                            <div class="login-form-item">
                                <input type="password" name="password" id="login-form-password" required class="--kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-round-2px --kuhu-border-auth-form --kuhu-font-16px --kuhu-font-600">
                            </div>
                        </div>

                    </div>

                    <div id="login-form-error-msg-box" class="--kuhu-margin-bottom-16px --kuhu-font-13px --kuhu-font-700">
                        <div id="login-form-error-msg">
                            <?php
                                echo $errorMsg;
                            ?>
                        </div>
                    </div>              

                    <div id="login-form-forgot-password-box" class="--kuhu-margin-bottom-16px">
                        <div id="login-form-forgot-password-msg" class="--kuhu-font-13px --kuhu-font-700 --kuhu-color-theme-dark --kuhu-hover-color-theme-darker">
                            I forgot my password
                        </div>
                    </div>

                    <div id="login-form-btn-box" class="--kuhu-margin-bottom-16px">
                        <div id="login-form-btn">
                            <input type="submit" value="Log in" class="--kuhu-width-100 --kuhu-padding-y-12px --kuhu-border-round-2px --kuhu-font-15px --kuhu-font-700 --kuhu-color-white --kuhu-bg-theme-dark --kuhu-hover-bg-theme-darker">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?= $liveRoot; ?>/assets/js/index.js"></script>
</body>

</html>
