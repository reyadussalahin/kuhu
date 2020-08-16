<?php
// this function returns post table data

// "post" table
// "CREATE TABLE IF NOT EXISTS post (
//     id VARCHAR(255) PRIMARY KEY,
//     user_id VARCHAR(255),
//     title VARCHAR(255),
//     post_time DATETIME
// )";


require_once("database/database.php");

require_once("components/user/getUsername.php");


function getPostTableData(&$posts) {
    $conn = Database::getConnection();

    // ok, so data retrieval from post table is successful
    // now, its time to retrieve post description blocks
    
    $sql = "SELECT user_id, title, datetime FROM post WHERE id = ?";

    foreach($posts as &$post) {
        $postID = $post["id"];

        if(!($stmt = $conn->prepare($sql))) {
            $conn->close();
            return "statement preparation for retrieving post texts(i.e. description blocks) using postID failed";
        }

        $stmt->bind_param("s", $postID);
        $stmt->execute();

        $userID = "";
        $postTitle = "";
        $postTime = "";
        if(!$stmt->bind_result($userID, $postTitle, $postTime)) {
            $stmt->close();
            $conn->close();
            return "parameter binding for retrieving description blocks from post_text table failed";
        }
        
        // $userID, $postTitle, $postTime...etc.. gets updated while $stmt->fetch() is called
        if($stmt->fetch()) {
            // store descriptonBlock in $descriptionBlocks array
            $post["by"] = getUsername($userID);
            $post["title"] = $postTitle;
            $post["datetime"] = $postTime;
        } else {
            return "post user/title/datetime etc. not found for a given postID";
        }

        $stmt->close(); // closing this statement is very important
        // otherwise, you can not create another statement with the same connection $conn
    }

    $conn->close();

    return true;
}

?>