<?php
// this function returns post table data

// "post" table
// "CREATE TABLE IF NOT EXISTS post (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     user_id VARCHAR(255) NOT NULL,
//     title TEXT NOT NULL DEFAULT '', -- note: default empty string
//     datetime DATETIME NOT NULL,
//     vote_count BIGINT NOT NULL DEFAULT 0,
//     comment_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
//     share_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
//     activeness BIGINT UNSIGNED NOT NULL DEFAULT 10000,
//     activeness_update_datetime DATETIME NOT NULL
// )";


require_once("database/database.php");

require_once("components/user/getUserID.php");



function getPostTableDataByUser(&$posts, $limit, $offset, $username) {

    $userID = getUserID($username);
    // check if userID is an error or not
    if(strpos($userID, " ") !== false) {
        return $userID; // cause $userID is then an error
    }


    $conn = Database::getConnection();

    $sql = "SELECT id, title, datetime, vote_count, comment_count, share_count
            FROM post
            WHERE user_id = ?
            ORDER BY datetime DESC
            LIMIT ? OFFSET ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving post table data using user id failed";
    }

    $stmt->bind_param("sii", $userID, $limit, $offset);
    $stmt->execute();
    
    $posts = []; // by default no posts

    $postID = ""; // by default
    $postTitle = ""; // by default
    $postDatetime = ""; // by default
    $voteCount = "";
    $commentCount = "";
    $shareCount = "";
    if(!$stmt->bind_result($postID, $postTitle, $postDatetime, $voteCount, $commentCount, $shareCount)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving post data from post table using user id failed";
    }
    
    // $postID, $postTitle...etc.. gets updated while $stmt->fetch() is called
    while($stmt->fetch()) {
        // store post in $posts array
        $posts[] = [
            "id" => $postID,
            "by" => $username,
            "title" => $postTitle,
            "dateTime" => $postDatetime,
            "vote-count" => $voteCount,
            "comment-count" => $commentCount,
            "share-count" => $shareCount
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