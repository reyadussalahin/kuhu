<?php

// this file provides function to check if a unverse exists or not

require_once("database/database.php");


function existsUniverse($universeName) {
    $conn = Database::getConnection();

    $sql = "SELECT id FROM universe WHERE name = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for checking if a universe exists failed";
    }
    
    $stmt->bind_param("s", $universeName);
    $stmt->execute();
    
    $universeID = -1; // by default
    if(!$stmt->bind_result($universeID)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving universeID in checking universe existance failed";
    }
    if(!$stmt->fetch()) {
        // no such universe exists
        // close everything and
        // return false
        $stmt->close();
        $conn->close();
        return false;
    }
    // else $universeID gets updated while $stmt->fetch() is called
    
    // but check for being hundred percent certain
    if($universeID === -1) {
        $stmt->close();
        $conn->close();
        return "universeID is not updated, even when statement executed successfully and fetched something, while trying to check if a universe exists";
    }

    // universe exists already
    // close everything
    $stmt->close();
    $conn->close();
    
    return true;
}

?>