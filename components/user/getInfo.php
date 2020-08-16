<?php

require_once("database/database.php");

function getInfo($username) {
    $conn = Database::getConnection();
    $sql = "SELECT id, first_name, last_name, email, register_datetime, last_login FROM user WHERE username = ?";
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving user info failed";
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $userID = -1; // by default
    $firstName = ""; // by default
    $lastName = ""; // by default
    $email = ""; // by default
    $registerTime = ""; // by default
    $lastLogin = ""; // by default
    if(!$stmt->bind_result($userID, $firstName, $lastName, $email, $registerTime, $lastLogin)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving user info failed";
    }
    if(!$stmt->fetch()) {
        // no such user exists
        $stmt->close();
        $conn->close();
        return "username not recognized";
    } // else $userID, $firstName...etc.. gets updated while $stmt->fetch() is called
    
    if($userID === -1) {
        $stmt->close();
        $conn->close();
        return "couldn't fetch user info even when statement executed succesfully when getInfo.php is called.";
    }

    $stmt->close();
    $conn->close();
    return [
        "userID" => $userID,
        "firstName" => $firstName,
        "lastName" => $lastName,
        "username" => $username,
        "email" => $email,
        "registerTime" => $registerTime,
        "lastLogin" => $lastLogin
    ];
}

?>