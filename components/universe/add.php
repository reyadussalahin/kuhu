<?php

// this file adds new universe in the universe table

require_once("database/database.php");

require_once("utils/universe/universeID.php");


// universe table
// "CREATE TABLE IF NOT EXISTS universe (
//     id VARCHAR(255) PRIMARY KEY,
//     name VARCHAR(255)
// )";

function addUniverse($universeName) {
    // note: this function doees not check if a universe exists or not
    //       it just add a given universeName into the table
    //       it also assumes, universeName given as the parameter
    //       is totally new and does not exists in the universe table

    $conn = Database::getConnection();

    $universeID = UniverseID::get();

    // inserting values in universe table
    $sql = "INSERT INTO universe (id, name) VALUES (?, ?)";
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for inserting values in universe table";
    }

    $stmt->bind_param("ss", $universeID, $universeName);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting values in universe table";
    }

    // ok, so add successfull
    // close everything
    $stmt->close();
    $conn->close();

    // increment universeID
    UniverseID::increment();
    
    return true;
}

?>