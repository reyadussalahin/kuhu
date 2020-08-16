<?php
require_once("settings/session.php");

// method to get logged in/current user's username
function getCurrentUsername() {
    if(!isset($_SESSION)) {
        session_set_cookie_params(Session::getLifetime());
        session_start();
    }
    if(isset($_SESSION["username"])) {
        return $_SESSION["username"];
    }
    return "";
}

// method to get current/logged in user id
function getCurrentUserID() {
    if(!isset($_SESSION)) {
        session_set_cookie_params(Session::getLifetime());
        session_start();
    }
    // note: user id is unsigned and auto incremented
    //       that's why returning -1 is perfectly fine
    if(isset($_SESSION["userID"])) {
        return $_SESSION["userID"];
    }
    return -1;
}

// method to return this sessions login time
function getLoginTime() {
    if(!isset($_SESSION)) {
        session_set_cookie_params(Session::getLifetime());
        session_start();
    }
    if(isset($_SESSION["login_time"])) {
        return $_SESSION["login_time"];
    }
    return -1;
}

?>