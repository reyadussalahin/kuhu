<?php
// this file handles how to insert upvote/downvote
// value for a post in the databse table

// related tables:
// post table:
// "CREATE TABLE IF NOT EXISTS post (
//     id VARCHAR(255) PRIMARY KEY,
//     user_id VARCHAR(255),
//     title VARCHAR(255),
//     post_time DATETIME,
//     vote_count BIGINT NOT NULL DEFAULT 0,
//     comment_count BIGINT NOT NULL DEFAULT 0,
//     share_count BIGINT NOT NULL DEFAULT 0,
//     activity_score BIGINT UNSIGNED NOT NULL DEFAULT 0
// )";


/**
 * post_vote: vote, post_id, user_id
 * (vote field can have three values)
 * (+1 means upvote)
 * (-1 means downvote)
 * (0 means, first provided upvote or downvote, but later the opposite)
 */
// "CREATE TABLE IF NOT EXISTS post_vote (
//     vote TINYINT,
//     post_id VARCHAR(255),
//     user_id VARCHAR(255)
// )";


require_once("database/database.php");

require_once("utils/user/currentUser.php");

require_once("utils/time/time.php");



function votePost($postID, $vote) {
    
    $todayDateTime = Time::bangladeshDateTime();

    $userID = getCurrentUserID();
    

    $conn = Database::getConnection();

    $sql = "SELECT vote from post_vote WHERE post_id = ? and user_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for getting vote status from post_vote table";
    }

    $stmt->bind_param("ss", $postID, $userID);
    $stmt->execute();

    $prevVote = ""; // just assigning an random default value
    if(!$stmt->bind_result($prevVote)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind result while trying to retrieve vote status from post_vote table using post_id and user_id";
    }
    // update $prevVote
    $fetchStatus = $stmt->fetch();

    $stmt->close();


    $bindVoteValue = "";
    $voteCountChange = 0;
    // $prevVotes value should be one of (-1, 0 or 1), if gets updated
    if(!$fetchStatus) { // if $prevVote not updated, then
        // user didn't vote previously
        $sql = "INSERT INTO post_vote (vote, post_id, user_id) VALUES (?, ?, ?)";
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
        $sql = "UPDATE post_vote SET vote = ? WHERE post_id = ? AND user_id = ?";
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
        return "couldn't prepare statement for inserting/updating values to post_vote table";
    }
    $stmt->bind_param("iss", $bindVoteValue, $postID, $userID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting/updating values in post_vote table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible


    // note: update_date_diff refers to the no of date between today and the last day when
    //       post activeness was updated
    // also note: we only need the difference the diff count of date, not the time
    //            so we've used DATEDIFF() function
    $sql = "SELECT vote_count, activeness, DATEDIFF(?, activeness_update_datetime) as update_date_diff FROM post WHERE id = ?";
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for retrieving value of vote_count, activeness and update_date_diff of post table";
    }

    $stmt->bind_param("ss", $todayDateTime, $postID);
    $stmt->execute();

    $voteCount = "";
    $activeness = "";
    $updateDateDiff = "";
    if(!$stmt->bind_result($voteCount, $activeness, $updateDateDiff)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind result for retireving vote_count, activeness and update_date_diff from post table";
    }
    if(!$stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return "couldn't fetch vote_count, activeness and update_date_diff value in post table though post id exists for sure";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible

    // now update each value
    // update vote_count value
    $voteCount += $voteCountChange;
    // now let's see for $activeness value 
    $updateDateDiff = intval($updateDateDiff);
    if($updateDateDiff > 0) {
        // if $activeness isn't subtracted by the necessary number in recent days
        // then,
        if($updateDateDiff >= 10) {
            // there's no activity for more than 10 days
            // then, just 
            $activeness = 0;
        } else if($activeness > 0) { // only update if $activeness > 0
            // first findout the subtract quantity
            // the subtractQuatity is equal to:
            // 1000..00, here the number zeros
            // is (the number of digits in activeness - 1)
            $subtractQuantity = 1;
            $num = $activeness;
            while($num > 0) {
                $num = intval($num / 10);
                $subtractQuantity *= 10;
            }
            $subtractQuantity = intval($subtractQuantity / 10);
            // activeness would be max of the previous level's greatest value or just
            // the value we get by subtracting proper quantity
            $activeness = max($subtractQuantity - 1, $activeness - $subtractQuantity);
        }
    }
    // note: each positive vote is +1
    //       and each negative vote is -1
    //       so, $activeness change will be
    //       the same as $voteCountChange
    $activeness += $voteCountChange;
    // $activeness can never be less than 0
    // so, put a check
    if($activeness < 0) {
        $activeness = 0;
    }

    // now insert update data into post table

    // now vote is properly updated,
    // we've to update "vote_count" of "post" table
    $sql = "UPDATE post SET vote_count = ?, activeness = ?, activeness_update_datetime = ? WHERE id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for updating value of vote_count, activeness, activeness_update_datetime of post table";
    }
    $stmt->bind_param("ssss", $voteCount, $activeness, $todayDateTime, $postID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for updating vote_count, activeness, activeness_update_datetime value in post table";
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