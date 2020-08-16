<?php

// reply table
// "CREATE TABLE IF NOT EXISTS reply (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     comment_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL,
//     datetime DATETIME NOT NULL,
//     reply_at VARCHAR(255) NOT NULL DEFAULT '', -- note: reply_at refers to the parent reply id
//     -- to which this reply is made
//     -- also note: this value will be null, if this reply is made to
//     -- a comment
//     vote_count BIGINT NOT NULL DEFAULT 0,
//     reply_reply_count BIGINT UNSIGNED NOT NULL DEFAULT 0 -- note: this is the count of replies
//     -- that has been made to this reply i.e. no of child of this replies
// )";

require_once("database/database.php");

require_once("components/user/getUsername.php");


function loadReplyTableDataRequested(&$replies, $commentID, $limit, $offset) {
    
    $conn = Database::getConnection();


    $sql = "SELECT id, user_id, datetime, reply_at, vote_count
            FROM reply
            WHERE comment_id = ?
            LIMIT ? OFFSET ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for retrieving data from reply table";
    }

    $stmt->bind_param("sii", $commentID, $limit, $offset);
    $stmt->execute();

    $replyID = "";
    $userID = "";
    $replyDatetime = "";
    $replyAt = "";
    $voteCount = "";
    if(!$stmt->bind_result($replyID, $userID, $replyDatetime, $replyAt, $voteCount)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving data from reply table failed";
    }

    // $replyID, $userID, ...etc.. gets updated while $stmt->fetch() is called
    while($stmt->fetch()) {
        $replies[] = [
            "id" => $replyID,
            "by" => getUsername($userID),
            "datetime" => $replyDatetime,
            "reply-at" => $replyAt,
            "vote-count" => $voteCount
        ];
    }

    $stmt->close(); // closing this statement is very important
    // otherwise, you can not create another statement with the same connection $conn


    $conn->close();

    return true;
}

?>