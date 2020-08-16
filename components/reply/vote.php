<?php
// this file handles how to insert upvote/downvote
// value for a reply in the databse table


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


// reply_vote table
// "CREATE TABLE IF NOT EXISTS reply_vote (
//     vote TINYINT NOT NULL,
//     reply_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL
// )";


require_once("database/database.php");

require_once("utils/user/currentUser.php");

require_once("utils/time/time.php");



function voteReply($replyID, $vote) {
    $userID = getCurrentUserID();
    
    // get connection
    $conn = Database::getConnection();

    $sql = "SELECT vote from reply_vote WHERE reply_id = ? and user_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for getting vote status from reply_vote table";
    }

    $stmt->bind_param("ss", $replyID, $userID);
    $stmt->execute();

    $prevVote = ""; // just assigning an random default value
    if(!$stmt->bind_result($prevVote)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind result while trying to retrieve vote status from reply_vote table using reply_id and user_id";
    }
    // update $prevVote
    $fetchStatus = $stmt->fetch();

    $stmt->close();


    $bindVoteValue = "";
    $voteCountChange = 0;
    // $prevVotes value should be one of (-1, 0 or 1), if gets updated
    if(!$fetchStatus) { // if $prevVote not updated, then
        // user didn't vote previously
        $sql = "INSERT INTO reply_vote (vote, reply_id, user_id) VALUES (?, ?, ?)";
        $bindVoteValue = $vote;
        if($vote === 1) {
            $voteCountChange++;
        } else {
            $voteCountChange--;
        }
    } else {
        // prev vote's updated
        // user provided a vote previously, and now
        // trying to update it
        $sql = "UPDATE reply_vote SET vote = ? WHERE reply_id = ? AND user_id = ?";
        if($vote === $prevVote) {
            // prev vote and vote are same
            // so user wants to remove his given vote
            // so, update the vote to 0
            $bindVoteValue = 0;
            if($prevVote === 1) {
                $voteCountChange--;
            } else {
                $voteCountChange++;
            }
        } else {
            // user wants to change his vote to another one
            // i.e. if he/she previously vote 1, now he/she wants to vote -1
            $bindVoteValue = $vote;
            if($prevVote === 0) {
                if($vote === 1) {
                    $voteCountChange++;
                } else {
                    $voteCountChange--;
                }
            } else if($prevVote === 1) {
                $voteCountChange -= 2;
            } else {
                $voteCountChange += 2;
            }
        }
    }

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for inserting/updating values to reply_vote table";
    }

    $stmt->bind_param("iss", $bindVoteValue, $replyID, $userID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting/updating values in reply_vote table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible



    // now, its time to get and update vote_count of reply table
    // and at the end we've to return updated vote_count value
    $sql = "SELECT vote_count FROM reply WHERE id = ?";
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for retrieving value of vote_count of reply table";
    }

    $stmt->bind_param("s", $replyID);
    $stmt->execute();

    $voteCount = "";
    if(!$stmt->bind_result($voteCount)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind result for retrieving vote_count from reply table";
    }
    if(!$stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return "couldn't fetch vote_count in reply table though reply id exists for sure";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible

    // now update each value
    // update vote_count value
    $voteCount += $voteCountChange;
    
    // now insert update data into reply table
    // we've to update "vote_count" of "reply" table
    $sql = "UPDATE reply SET vote_count = ? WHERE id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for updating value of vote_count of reply table";
    }
    
    $stmt->bind_param("ss", $voteCount, $replyID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for updating vote_count value in reply table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible
    
    $conn->close();

    // now, just return the vote count
    return $voteCount;
}

?>