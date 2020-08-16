<?php
// this file declares function to retrieve id from universe table


require_once("database/database.php");

require_once("components/universe/exists.php");
require_once("components/universe/add.php");


function getUniverseID($universeName) {
    // note: if a universe name does not exist in the database
    //       then, this function add the universe name in the
    //       database and get the id later
    //       this may be a bit slow
    //       but it'll do for now
    //       we'll refactor it to a faster version later

    $existsStatus = existsUniverse($universeName);
    if(($existsStatus !== true) && ($existsStatus !== false)) {
        // then, $existsStatus is an error
        return $existsStatus;
    }
    if($existsStatus === false) {
        $addStatus = addUniverse($universeName);
        if($addStatus !== true) {
            // then, $addStatus is an error
            return $addStatus;
        }
    }
    
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
        return false;
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