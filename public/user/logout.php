<?php

require_once("settings/settings.php");
require_once("components/user/authentication.php");

$liveRoot = Settings::getLiveRoot();

$unauthenticateStatus = unauthenticate();

if($unauthenticateStatus === true) {
    header("Location: $liveRoot/index.php");
    exit();
} else {
    echo "<p>" . $unauthenticateStatus . "</p>";
}

?>