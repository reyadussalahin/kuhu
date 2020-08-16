<?php

require_once("settings/settings.php");

require_once("components/user/authentication.php");
require_once("components/user/register.php");

require_once("components/user/validation.php");
require_once("components/user/filter.php");


$liveRoot = Settings::getLiveRoot();

// if already logged in, then just head back to index.php
if(isAuthenticated()) {
    header("Location: $liveRoot/index.php");
	exit();
}

$firstName = "";
$lastName = "";
$username = "";
$email = "";
$password = "";

$firstNameErrorMsg = "";
$lastNameErrorMsg = "";
$usernameErrorMsg = "";
$emailErrorMsg = "";
$passwordErrorMsg = "";


if($_SERVER["REQUEST_METHOD"] === "POST") {
    // if method == post, then filter, validate and register user

    $firstName = $_POST["first-name"];
    $lastName = $_POST["last-name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // we need to trim firstName, lastName, username, and email to get rid of
    // trailing spaces
    $firstName = filterFirstName($firstName);
    $lastName = filterLastName($lastName);
    $username = filterUsername($username);
    $email = filterEmail($email);

    // validate all fields
    $firstNameErrorMsg = validateFirstName($firstName);
    $lastNameErrorMsg = validateLastName($lastName);
    $usernameErrorMsg = validateUsername($username);
    $emailErrorMsg = validateEmail($email);
    $passwordErrorMsg = validatePassword($password);

    if(!($firstNameErrorMsg || $lastNameErrorMsg || $usernameErrorMsg || $emailErrorMsg || $passwordErrorMsg)) {
        // no error found during validation
        // so, registering user
        $registerStatus = register($firstName, $lastName, $username, $email, $password);
        if($registerStatus === true) {
            // registration successfull
            // now authenticating user directly after registration
            $authenticationStatus = authenticate($username, $password);
            if($authenticationStatus === true) {
                header("Location: $liveRoot/index.php"); // the index file path should be given according to document root
	            exit();
            } else {
                echo "<p>" . $authenticationStatus . "</p>";
                exit(1);
            }
        } else {
            echo "<p>" . $registerStatus . "</p>";
            exit(1);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
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

        <div class="--kuhu-box --kuhu-margin-top-32px --kuhu-margin-x-auto --kuhu-padding-bottom-64px --kuhu-max-width-400px">
            <div id="signup-form-wrapper" class="--kuhu-box --kuhu-padding-x-32px">

                <div id="signup-form-prefix" class="--kuhu-margin-bottom-16px --kuhu-font-20px --kuhu-font-700 --kuhu-text-center --kuhu-color-theme">
                    Sign Up to Kuhu
                </div>

                <form id="signup-form" action="" method="POST">

                    <div id="signup-form-item-container">
                    
                        <div class="signup-form-item-box signup-form-item-box --kuhu-margin-top-16px --kuhu-font-13px --kuhu-font-700">
                            <div class="signup-form-item-prefix signup-form-item-prefix --kuhu-margin-bottom-6px --kuhu-color-hx-666">
                                <label for="signup-form-first-name">First Name</label>
                            </div>

                            <div class="signup-form-item">
                                <input type="text" name="first-name" id="signup-form-first-name" value="<?= $firstName;?>" minlength="<?= firstNameMinLen();?>" maxlength="<?= firstNameMaxLen()?>" required class="--kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-round-2px --kuhu-border-auth-form --kuhu-font-16px --kuhu-font-600">
                            </div>
                            
                            <div id="first-name-error-msg" class="signup-form-error-msg">
                                <?= $firstNameErrorMsg;?>
                            </div>
                        </div>

                        <div class="signup-form-item-box signup-form-item-box --kuhu-margin-top-16px --kuhu-font-13px --kuhu-font-700">
                            <div class="signup-form-item-prefix signup-form-item-prefix --kuhu-margin-bottom-6px --kuhu-color-hx-666">
                                <label for="signup-form-last-name">Last Name</label>
                            </div>

                            <div class="signup-form-item">
                                <input type="text" name="last-name" id="signup-form-last-name" value="<?= $lastName;?>" minlength="<?= lastNameMinLen();?>" maxlength="<?= lastNameMaxLen()?>" required class="--kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-round-2px --kuhu-border-auth-form --kuhu-font-16px --kuhu-font-600">
                            </div>
                            
                            <div id="last-name-error-msg" class="signup-form-error-msg">
                                <?= $lastNameErrorMsg;?>
                            </div>
                        </div>

                        <div class="signup-form-item-box signup-form-item-box --kuhu-margin-top-16px --kuhu-font-13px --kuhu-font-700">
                            <div class="signup-form-item-prefix signup-form-item-prefix --kuhu-margin-bottom-6px --kuhu-color-hx-666">
                                <label for="signup-form-username">Username</label>
                            </div>

                            <div class="signup-form-item">
                                <input type="text" name="username" id="signup-form-username" value="<?= $username;?>" minlength="<?= usernameMinLen();?>" maxlength="<?= usernameMaxLen()?>" required class="--kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-round-2px --kuhu-border-auth-form --kuhu-font-16px --kuhu-font-600">
                            </div>

                            <div id="username-error-msg" class="signup-form-error-msg">
                                <?= $usernameErrorMsg;?>
                            </div>
                        </div>

                        <div class="signup-form-item-box signup-form-item-box --kuhu-margin-top-16px --kuhu-font-13px --kuhu-font-700">
                            <div class="signup-form-item-prefix signup-form-item-prefix --kuhu-margin-bottom-6px --kuhu-color-hx-666">
                                <label for="signup-form-email">Email</label>
                            </div>

                            <div class="signup-form-item">
                                <input type="email" name="email" id="signup-form-email" value="<?= $email;?>" required class="--kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-round-2px --kuhu-border-auth-form --kuhu-font-16px --kuhu-font-600">
                            </div>

                            <div id="email-error-msg" class="signup-form-error-msg">
                                <?= $emailErrorMsg;?>
                            </div>
                        </div>

                        <div class="signup-form-item-box signup-form-item-box --kuhu-margin-top-16px --kuhu-font-13px --kuhu-font-700">
                            <div class="signup-form-item-prefix signup-form-item-prefix --kuhu-margin-bottom-6px --kuhu-color-hx-666">
                                <label for="signup-form-password">Password</label>
                            </div>

                            <div class="signup-form-item">
                                <input type="password" name="password" id="signup-form-password" minlength="<?= passwordMinLen();?>" required class="--kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-round-2px --kuhu-border-auth-form --kuhu-font-16px --kuhu-font-600">
                            </div>

                            <div id="password-error-msg" class="signup-form-error-msg">
                                <?= $passwordErrorMsg;?>
                            </div>
                        </div>
                    </div>
                    
                    <div id="signup-form-btn-box" class="--kuhu-margin-top-32px">
                        <div id="signup-form-btn">
                            <input type="submit" value="Sign up" class="--kuhu-width-100 --kuhu-padding-y-12px --kuhu-border-round-2px --kuhu-font-15px --kuhu-font-700 --kuhu-color-white --kuhu-bg-theme-dark --kuhu-hover-bg-theme-darker">
                        </div>
                    </div>

                    <div id="signup-form-already-have-an-account-box" class="--kuhu-margin-top-16px --kuhu-font-15px --kuhu-text-center">
                        <div id="signup-form-already-have-an-account-msg">
                            Already have an account?
                            <span id="signup-form-already-have-an-account-login" class="--kuhu-font-700">
                                <a href="<?= $liveRoot; ?>/user/login.php" class="--kuhu-color-theme --kuhu-hover-color-theme-darker">
                                Login here!
                                </a>
                            </span>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
    
</body>

</html>