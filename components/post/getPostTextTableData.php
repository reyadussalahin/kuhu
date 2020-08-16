<?php

require_once("database/database.php");


// "post_text" table
// "CREATE TABLE IF NOT EXISTS post_text (
//     id VARCHAR(255) PRIMARY KEY,
//     post_id VARCHAR(255),
//     text TEXT,
//     position INT
// )";


function getPostTextTableData(&$posts) {

    $conn = Database::getConnection();

    // ok, so data retrieval from post table is successful
    // now, its time to retrieve post description blocks
    
    $sql = "SELECT text, position FROM post_text WHERE post_id = ?";

    foreach($posts as &$post) {
        
        $postID = $post["id"];

        if(!($stmt = $conn->prepare($sql))) {
            $conn->close();
            return "statement preparation for retrieving post texts(i.e. description blocks) using postID failed";
        }

        $stmt->bind_param("s", $postID);
        $stmt->execute();

        // we've taken this way cause,
        // descriptionBlocks are distributed
        // through texts, images, videos etc...
        // but if post does not have a description block, then 
        if(!isset($post["descriptionBlocks"])) {
            $post["descriptionBlocks"] = [];
        }
        $descriptionBlocks = &$post["descriptionBlocks"];

        $descriptionBlock = ""; // by default
        $position = ""; // by default
        if(!$stmt->bind_result($descriptionBlock, $position)) {
            $stmt->close();
            $conn->close();
            return "parameter binding for retrieving description blocks from post_text table failed";
        }
        
        // $descriptionBlock, $position...etc.. gets updated while $stmt->fetch() is called
        while($stmt->fetch()) {
            // store descriptonBlock in $descriptionBlocks array
            // note: position is always a unique integer
            $idx = intval($position);
            $descriptionBlocks[$idx] = $descriptionBlock;
        }
        
        if(count($descriptionBlocks) === 0) {
            // $stmt->close();
            // $conn->close();
            // return "post data not found while searching post data in post table";

            // this time we're just ignoring it
            // cause no description is provided in this post
            // this post may be contains only title
        }

        // no need to assign it
        // cause $descriptionBlocks is reference to $post["descriptionBlocks]
        // $post["descriptionBlocks"] = $descriptionBlocks;

        $stmt->close(); // closing this statement is very important
        // otherwise, you can not create another statement with the same connection $conn
    }

    $conn->close();

    return true;
}

?>