<?php

// comment_vote table
// "CREATE TABLE IF NOT EXISTS comment_vote (
//     vote TINYINT NOT NULL DEFAULT 0,
//     comment_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL
// )";


require_once("database/database.php");

require_once("components/user/authentication.php");

require_once("utils/user/currentUser.php");


function getCommentSelfVoteStatus(&$comments) {
    // if not authenticated
    // then, no point of getting this data
    if(!isAuthenticated()) {
        return true;
    }

    $currentUserID = getCurrentUserID();

    
    $conn = Database::getConnection();

    $sql = "SELECT vote FROM comment_vote WHERE user_id = ? AND comment_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving comment self vote status using user id and comment id has failed";
    }

    // by default
    $commentID = "";

    $stmt->bind_param("ss", $currentUserID, $commentID);

    foreach($comments as &$comment) {
        $commentID = $comment["id"];

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
                $comment["self-vote"] = "u";
            } else if($vote === -1) {
                $comment["self-vote"] = "d";
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