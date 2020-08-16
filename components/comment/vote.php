<?php
// this file handles how to insert upvote/downvote
// value for a comment in the databse table

// related tables:
// comment table:
// "CREATE TABLE IF NOT EXISTS comment (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     post_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL,
//     datetime DATETIME NOT NULL,
//     vote_count BIGINT NOT NULL DEFAULT 0,
//     reply_count BIGINT UNSIGNED NOT NULL DEFAULT 0
// )";

// comment_vote table
// "CREATE TABLE IF NOT EXISTS comment_vote (
//     vote TINYINT NOT NULL DEFAULT 0,
//     comment_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL
// )";


require_once("database/database.php");

require_once("utils/user/currentUser.php");

require_once("utils/time/time.php");



function voteComment($commentID, $vote) {
    $userID = getCurrentUserID();
    
    // get connection
    $conn = Database::getConnection();

    $sql = "SELECT vote from comment_vote WHERE comment_id = ? and user_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for getting vote status from comment_vote table";
    }

    $stmt->bind_param("ss", $commentID, $userID);
    $stmt->execute();

    $prevVote = ""; // just assigning an random default value
    if(!$stmt->bind_result($prevVote)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind result while trying to retrieve vote status from comment_vote table using comment_id and user_id";
    }
    // update $prevVote
    $fetchStatus = $stmt->fetch();

    $stmt->close();


    $bindVoteValue = "";
    $voteCountChange = 0;
    // $prevVotes value should be one of (-1, 0 or 1), if gets updated
    if(!$fetchStatus) { // if $prevVote not updated, then
        // user didn't vote previously
        $sql = "INSERT INTO comment_vote (vote, comment_id, user_id) VALUES (?, ?, ?)";
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
        $sql = "UPDATE comment_vote SET vote = ? WHERE comment_id = ? AND user_id = ?";
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
        return "couldn't prepare statement for inserting/updating values to comment_vote table";
    }
    $stmt->bind_param("iss", $bindVoteValue, $commentID, $userID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting/updating values in comment_vote table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible



    // now, its time to get and update vote_count of comment table
    // and at the end we've to return updated vote_count value
    $sql = "SELECT vote_count FROM comment WHERE id = ?";
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for retrieving value of vote_count of comment table";
    }

    $stmt->bind_param("s", $commentID);
    $stmt->execute();

    $voteCount = "";
    if(!$stmt->bind_result($voteCount)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind result for retrieving vote_count from comment table";
    }
    if(!$stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return "couldn't fetch vote_count in comment table though comment id exists for sure";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible

    // now update each value
    // update vote_count value
    $voteCount += $voteCountChange;
    
    // now insert update data into comment table
    // we've to update "vote_count" of "comment" table
    $sql = "UPDATE comment SET vote_count = ? WHERE id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for updating value of vote_count of comment table";
    }

    $stmt->bind_param("ss", $voteCount, $commentID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for updating vote_count value in comment table";
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