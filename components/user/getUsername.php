<?php

require_once("database/database.php");


function getUsername($userID) {
    // note: this function assumes that
    //       the provided userID exists
    //       already

    $conn = Database::getConnection();

    $sql = "SELECT username FROM user WHERE id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving username using userID failed";
    }

    $stmt->bind_param("s", $userID);
    $stmt->execute();
    
    $username = ""; // by default
    if(!$stmt->bind_result($username)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving username using userID failed";
    }
    if(!$stmt->fetch()) {
        // no such user exists
        $stmt->close();
        $conn->close();
        return "userID not found while searching username using the provided UserID";
    } // else $username..etc.. gets updated while $stmt->fetch() is called
    
    if($username === "") {
        $stmt->close();
        $conn->close();
        return "couldn't fetch username even when statement executed succesfully and fetch return true when getUsername is called.";
    }

    $stmt->close();
    $conn->close();

    return $username;
}

?>