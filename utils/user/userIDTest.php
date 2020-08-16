<?php
// this file is for testing userID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "userID.php"); // for testing purposes
require_once("utils/user/userID.php");

// userID::reset();

for($i=0; $i<100; $i++) {
    $userID = UserID::get();
    echo "user_id: " . $userID . "\n";
    UserID::increment();
}

?>