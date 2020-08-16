<?php

// this inserts data into database

// post table
// "CREATE TABLE IF NOT EXISTS post (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     user_id VARCHAR(255) NOT NULL,
//     title TEXT NOT NULL DEFAULT '', -- note: default empty string
//     datetime DATETIME NOT NULL,
//     vote_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
//     comment_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
//     share_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
//     activeness BIGINT UNSIGNED NOT NULL DEFAULT 10000,
//     -- note
//     activeness_update_date DATE NOT NULL
// )";

// "post_text" table
// "CREATE TABLE IF NOT EXISTS post_text (
//     id VARCHAR(255) PRIMARY KEY,
//     post_id VARCHAR(255),
//     text TEXT,
//     position INT
// )";


// "post_universe" table
// "CREATE TABLE IF NOT EXISTS post_universe (
//     post_id VARCHAR(255),
//     universe_id VARCHAR(255)
// )";


require_once("database/database.php");


require_once("components/universe/getIDs.php");

require_once("utils/user/currentUser.php");

require_once("utils/time/time.php");
require_once("utils/post/postID.php");
require_once("utils/postText/postTextID.php");


function addPost($title, $descriptionBlocks, $universes) {
    $postID = PostID::get();
    $postDatetime = Time::bangladeshDateTime();
    $activenessUpdateDate = Time::bangladeshDateTime();
    $userID = getCurrentUserID();

    $conn = Database::getConnection();

    // inserting values in post table
    $sql = "INSERT INTO post (id, user_id, title, datetime, activeness_update_datetime) VALUES (?, ?, ?, ?, ?)";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for inserting values in post table";
    }

    $stmt->bind_param("sssss", $postID, $userID, $title, $postDatetime, $activenessUpdateDate);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting values in post table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible


    // ok, so inserting values in post table is a success
    // now, its time to add values in post_text table
    $sql = "INSERT INTO post_text (id, post_id, text, position) VALUES (?, ?, ?, ?)";
    
    $blockLength = count($descriptionBlocks);

    for($i = 0; $i < $blockLength; $i++) {
        $block = $descriptionBlocks[$i];
        
        $postTextID = PostTextID::get();
        
        if(!($stmt = $conn->prepare($sql))) {
            $conn->close();
            return "couldn't prepare statement for inserting values in post_text table";
        }
        $stmt->bind_param("sssi", $postTextID, $postID, $block, $i);
        if(!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "couldn't execute statement for inserting values in post_text table";
        }

        PostTextID::increment();

        $stmt->close(); // closing $stmt is very important
    }
    
    // ok so inserting values in post_text table is also a success
    
    // now, we've to insert values into post_universe table

    // first get the ids of universes
    $universeIDs = getUniverseIDs($universes);
    
    // check if an error is occured
    if(gettype($universeIDs) !== "array") {
        return $universeIDs; // cause it is an error
    }

    // so, universeIDs retrieved successfully

    $sql = "INSERT INTO post_universe (post_id, universe_id) VALUES (?, ?)";
    
    foreach($universeIDs as $universeID) {
        if(!($stmt = $conn->prepare($sql))) {
            $conn->close();
            return "couldn't prepare statement for inserting values in post_universe table";
        }

        $stmt->bind_param("ss", $postID, $universeID);
        
        if(!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "couldn't execute statement for inserting values in post_universe table";
        }
        
        $stmt->close(); // closing $stmt is very important
    }

    // ok insertion in post_universe table is also successful

    // so, now, we can increment postid
    PostID::increment();
    
    // at last close the connection
    $conn->close();

    return $postID;
}

?>