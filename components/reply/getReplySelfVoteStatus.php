<?php

// reply_vote table
// "CREATE TABLE IF NOT EXISTS reply_vote (
//     vote TINYINT NOT NULL DEFAULT 0,
//     reply_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL
// )";


require_once("database/database.php");

require_once("components/user/authentication.php");

require_once("utils/user/currentUser.php");


function getReplySelfVoteStatus(&$replies) {
    // if not authenticated
    // then, no point of getting this data
    if(!isAuthenticated()) {
        return true;
    }

    $currentUserID = getCurrentUserID();

    
    $conn = Database::getConnection();

    $sql = "SELECT vote FROM reply_vote WHERE user_id = ? AND reply_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving reply self vote status using user id and reply id has failed";
    }

    // by default
    $replyID = "";

    $stmt->bind_param("ss", $currentUserID, $replyID);

    foreach($replies as &$reply) {
        $replyID = $reply["id"];

        // execute statement
        $stmt->execute();

        $vote = ""; // default value
        // bind result variable
        $stmt->bind_result($vote);

        // fetch result
        if($stmt->fetch()) {
            // so, vote has updated
            $vote = intval($vote);
            if($vote === 1) {
                $reply["self-vote"] = "u";
            } else if($vote === -1) {
                $reply["self-vote"] = "d";
            }
        }
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible


    $conn->close();

    return true;
}

?>