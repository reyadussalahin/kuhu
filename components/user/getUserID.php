<?php

require_once("database/database.php");


function getUserID($username) {
    // note: this function assumes that
    //       the provided username exists
    //       already in database

    $conn = Database::getConnection();

    $sql = "SELECT id FROM user WHERE username = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving UserID using username failed";
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $userID = ""; // by default
    if(!$stmt->bind_result($userID)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving userID using username failed";
    }
    if(!$stmt->fetch()) {
        // no such user exists
        $stmt->close();
        $conn->close();
        return "userID not fetched while searching userID using the provided username";
    } // else $userID..etc.. gets updated while $stmt->fetch() is called
    
    if($userID === "") {
        $stmt->close();
        $conn->close();
        return "couldn't fetch userID even when statement executed succesfully and fetch return true when getUserID is called.";
    }

    $stmt->close();
    $conn->close();

    return $userID;
}

?>