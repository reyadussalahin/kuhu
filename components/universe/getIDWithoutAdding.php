<?php
// this file declares function to retrieve id from universe table


require_once("database/database.php");


function getUniverseIDWithoutAdding($universeName) {

    $conn = Database::getConnection();

    $sql = "SELECT id FROM universe WHERE name = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving universeID failed";
    }
    
    $stmt->bind_param("s", $universeName);
    $stmt->execute();
    
    $universeID = -1; // by default
    if(!$stmt->bind_result($universeID)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving universeID failed";
    }
    if(!$stmt->fetch()) {
        // no such universe exists
        // close everything and
        // return false
        $stmt->close();
        $conn->close();
        return false; // i.e. $universeID not found
    }
    // else $universeID gets updated while $stmt->fetch() is called
    
    // but check for being hundred percent certain
    if($universeID === -1) {
        $stmt->close();
        $conn->close();
        return "universeID is not updated, even when statement executed successfully and fetched something, while trying to check if a universe exists";
    }
    
    // universeID found
    // so, close everything
    // and return the ID
    $stmt->close();
    $conn->close();
    
    return $universeID;
}

?>