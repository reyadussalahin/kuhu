<?php
// reply_text table
// "CREATE TABLE IF NOT EXISTS reply_text (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     reply_id VARCHAR(255) NOT NULL,
//     text TEXT NOT NULL DEFAULT '',
//     position INT UNSIGNED NOT NULL
// )";

require_once("database/database.php");


function getReplyTextTableData(&$replies) {
    $conn = Database::getConnection();

    $sql = "SELECT text FROM reply_text WHERE reply_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for retrieving data from reply_text table";
    }

    $replyID = ""; // default
    
    $stmt->bind_param("s", $replyID);

    foreach($replies as &$reply) {
        $replyID = $reply["id"];

        if(!$stmt->execute()) {
            return "couldn't execute statement for retrieving reply_text table data though reply_id exists";
        }

        $replyText = ""; // default
        $stmt->bind_result($replyText);

        if(!$stmt->fetch()) {
            return "couldn't fetch text data for reply_text though reply_id exists";
        }

        $reply["text"] = $replyText;
    }


    $stmt->close();


    $conn->close();

    return true;
}

?>