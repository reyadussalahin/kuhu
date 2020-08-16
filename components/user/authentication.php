<?php

require_once("database/database.php");
require_once("settings/session.php");
require_once("utils/time/time.php");

// method to check if user is already logged in
function isAuthenticated() {
    if(!isset($_SESSION)) {
        session_set_cookie_params(Session::getLifetime());
        session_start();
    }
    if(isset($_SESSION["username"])) {
        return true;
    }
    return false;
}

// method to authenticate user
function authenticate($username, $password) {
    // matching username and password in database for authentication
    $conn = Database::getConnection();
    $sql = "SELECT id FROM user WHERE username = ? and password = ?";
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving username and password for authentication failed";
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    
    $userID = -1; // by default
    if(!$stmt->bind_result($userID)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving userId failed";
    }
    if(!$stmt->fetch()) {
        // no such user exists
        $stmt->close();
        $conn->close();
        return "username/password not recognized";
    } // else $userID gets updated while $stmt->fetch() is called

    if($userID === -1) {
        return "userID is not updated, even when statement executed successfully and fetched something, while trying to match username/password combination";
    }

    // username and userId and session login time are added to session
    if(!isset($_SESSION)) {
        session_set_cookie_params(Session::getLifetime());
        session_start();
    }
    $_SESSION["username"] = $username;
    $_SESSION["userID"] = $userID;
    // note: Time::bangladeshDateTime() returns $_SERVER["REQUEST_TIME"](which is "UTC" in my pc) + (6 hours)
    $_SESSION["login_time"] = Time::bangladeshDateTime();

    // it's very important to close previous statement
    // before creating a new statement with the same connection
    // otherwise, error occurs i.e. cannot prepare new statement
    // so, closing previous statement
    $stmt->close();

    // updating last_login in the user table for username/userId
    $sql = "UPDATE user SET last_login = ? WHERE id = ?";
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement creation for updating last login time for user failed";
    }
    
    $stmt->bind_param("ss", $_SESSION["login_time"], $userID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "execution for updating last login time for user failed";
    }

    if($stmt->affected_rows !== 1) {
        $stmt->close();
        $conn->close();
        return "last login for user not updated perfectly. please contact database administrator.";
    }

    $stmt->close();
    $conn->close();
    return true;
}

function unauthenticate() {
    if(!isAuthenticated()) {
        return "you haven't logged in yet.";
    }
    /**
     * as session must start before destroy
     */
    if(!isset($_SESSION)) {
        session_start();
    }

    /**
     * reinitialize $_SESSION var
     */
    $_SESSION = array();

    /**
     * deleting cookie from remote client
     */
    setcookie(session_name(), "", time() - Session::getLifetime());

    /**
     * finally destroying all $_SESSION variables those were set
     */
    session_destroy();
    return true;
}

?>