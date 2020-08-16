<?php
// this function returns post table data


require_once("database/database.php");

require_once("components/universe/getIDWithoutAdding.php");


// "post_universe" table
// "CREATE TABLE IF NOT EXISTS post_universe (
//     post_id VARCHAR(255),
//     universe_id VARCHAR(255)
// )";



function getPostUniverseTableByUniverse(&$posts, $universe) {
    
    // retrieve $universeID from $universe
    $universeID = getUniverseIDWithoutAdding($universe);

    // in case $universeID not found
    if($universeID === false) {
        return false; // $universeID not found
    }
    // check for error
    // $universeID would never have space
    // unless it is an error
    if(strpos($universeID, " ") !== false) {
        return $universeID; // cause it is an error
    }


    $conn = Database::getConnection();
    
    $sql = "SELECT post_id FROM post_universe WHERE universe_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving postID using universeID failed";
    }

    $stmt->bind_param("s", $universeID);
    $stmt->execute();

    $postID = ""; // by default
    if(!$stmt->bind_result($postID)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving postID from post_universe table failed";
    }
    
    // $postID gets updated while $stmt->fetch() is called
    while($stmt->fetch()) {
        // store post in $posts array
        $posts[] = [
            "id" => $postID
        ];
    }
    
    if(count($posts) === 0) {
        // $stmt->close();
        // $conn->close();
        // return "post data not found while searching post data in post table";
        // this is only possible when the website just started
        // so, do nothing
    }

    $stmt->close(); // closing this statement is very important
    // otherwise, you can not create another statement with the same connection $conn

    $conn->close();

    return true;
}

?>