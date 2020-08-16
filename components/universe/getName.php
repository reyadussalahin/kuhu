<?php

require_once("database/database.php");


function getUniverseName($universeID) {
    // note: this function assumes that
    //       the provided universeID exists
    //       already(must), otherwise an error

    $conn = Database::getConnection();

    $sql = "SELECT name FROM universe WHERE id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving universeName using universeID failed";
    }

    $stmt->bind_param("s", $universeID);
    $stmt->execute();
    
    $universeName = ""; // by default
    if(!$stmt->bind_result($universeName)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving universeName using universeID failed";
    }
    if(!$stmt->fetch()) {
        // no such universe exists
        $stmt->close();
        $conn->close();
        return "universeID not found while searching universeName using the provided universeID";
    } // else $universeName gets updated while $stmt->fetch() is called
    
    if($universeName === "") {
        $stmt->close();
        $conn->close();
        return "couldn't fetch universeName even when statement executed succesfully and fetch return true when getName is called.";
    }

    $stmt->close();
    $conn->close();

    return $universeName;
}

?>