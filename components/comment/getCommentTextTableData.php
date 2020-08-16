<?php
// comment_text table
// "CREATE TABLE IF NOT EXISTS comment_text (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     comment_id VARCHAR(255) NOT NULL,
//     text TEXT NOT NULL DEFAULT '',
//     position INT UNSIGNED NOT NULL
// )";


require_once("database/database.php");


function getCommentTextTableData(&$comments) {
    $conn = Database::getConnection();

    $sql = "SELECT text FROM comment_text WHERE comment_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for retrieving data from comment_text table";
    }

    $commentID = ""; // default
    
    $stmt->bind_param("s", $commentID);

    foreach($comments as &$comment) {
        $commentID = $comment["id"];

        if(!$stmt->execute()) {
            return "couldn't execute statement for retrieving comment_text table data though comment_id exists";
        }

        $commentText = ""; // default
        $stmt->bind_result($commentText);

        if(!$stmt->fetch()) {
            return "couldn't fetch text data for comment_text though comment_id exists";
        }

        $comment["text"] = $commentText;
    }


    $stmt->close();


    $conn->close();

    return true;
}

?>