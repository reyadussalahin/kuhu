<?php


// comment table
// "CREATE TABLE IF NOT EXISTS comment (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     post_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL,
//     datetime DATETIME NOT NULL,
//     vote_count BIGINT NOT NULL DEFAULT 0,
//     reply_count BIGINT UNSIGNED NOT NULL DEFAULT 0
// )";

require_once("database/database.php");

require_once("components/user/getUsername.php");



function loadCommentTableDataRequested(&$comments, $postID, $limit, $offset) {
    
    $conn = Database::getConnection();


    $sql = "SELECT id, user_id, datetime, vote_count, reply_count
            FROM comment
            WHERE post_id = ?
            LIMIT ? OFFSET ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for retrieving data from comment table";
    }

    $stmt->bind_param("sii", $postID, $limit, $offset);
    $stmt->execute();

    $commentID = "";
    $userID = "";
    $commentDatetime = "";
    $voteCount = "";
    $replyCount = "";
    if(!$stmt->bind_result($commentID, $userID, $commentDatetime, $voteCount, $replyCount)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving data from comment table failed";
    }

    // $commentID, $userID, ...etc.. gets updated while $stmt->fetch() is called
    while($stmt->fetch()) {
        $comments[] = [
            "id" => $commentID,
            "by" => getUsername($userID),
            "datetime" => $commentDatetime,
            "vote-count" => $voteCount,
            "reply-count" => $replyCount
        ];
    }

    $stmt->close(); // closing this statement is very important
    // otherwise, you can not create another statement with the same connection $conn


    $conn->close();

    return true;
}

?>