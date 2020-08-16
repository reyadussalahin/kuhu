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

// comment_text table
// "CREATE TABLE IF NOT EXISTS comment_text (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     comment_id VARCHAR(255) NOT NULL,
//     text TEXT NOT NULL DEFAULT '',
//     position INT UNSIGNED NOT NULL
// )";


require_once("database/database.php");

require_once("utils/user/currentUser.php");

require_once("utils/time/time.php");
require_once("utils/comment/commentID.php");
require_once("utils/commentText/commentTextID.php");


function addComment($postID, $commentText) {
    $commentID = CommentID::get();
    $commentDatetime = Time::bangladeshDateTime();
    $userID = getCurrentUserID();

    $conn = Database::getConnection();

    // inserting values in comment table
    $sql = "INSERT INTO comment (id, post_id, user_id, datetime) VALUES (?, ?, ?, ?)";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for inserting values in comment table";
    }

    $stmt->bind_param("ssss", $commentID, $postID, $userID, $commentDatetime);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting values in comment table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible


    // ok, so inserting values in comment table is a success
    // now, its time to add values in comment_text table

    // note: we assume to order comment contents in blocks which have positions
    //       like:
    //       block-position-01: contains some text
    //       block-position-02: contains one/two/more image(s)
    //       block-position-03: again some texts
    //       block-position-04: a video
    //       etc...
    //       each block is distributed in text, image, video etc... tables
    //       but there position is unique when we merge them
    //       *** this block by block comment content management is right now not
    //           implemented. We'd look to it in near future
    //           for simplicity we're now considering only texts

    $sql = "INSERT INTO comment_text (id, comment_id, text, position) VALUES (?, ?, ?, ?)";
    
    // at first, get comment text id
    $commentTextID = commentTextID::get();
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for inserting values in comment_text table";
    }

    $defaultPosition = 999999999; // just for filling the value as "position" column is "NOT NULL"
    
    $stmt->bind_param("sssi", $commentTextID, $commentID, $commentText, $defaultPosition);
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting values in comment_text table";
    }


    commentTextID::increment();

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible

    CommentID::increment();
    


    // now, we've to update the activeness of the post
    // you should remember, each comment of a post
    // increase the activeness of a post by +2

    // first check if we've subtracted the daily basis subtractor quantity
    $sql = "SELECT comment_count, activeness, DATEDIFF(?, activeness_update_datetime) as update_date_diff FROM post WHERE id = ?";
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for retrieving value of activeness and update_date_diff of post table while adding comment";
    }

    $stmt->bind_param("ss", $commentDatetime, $postID);
    $stmt->execute();

    $commentCount = "";
    $activeness = "";
    $updateDateDiff = "";
    if(!$stmt->bind_result($commentCount, $activeness, $updateDateDiff)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind result for retireving comment_count, activeness and update_date_diff from post table while adding comment";
    }
    if(!$stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return "couldn't fetch comment_count, activeness and update_date_diff value in post table though post id exists for sure while adding comment";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible



    // now its time to update post table data

    // now comment is added in comment table
    // so, increase comment count
    $commentCount += 1;

    // now let's see if we've to subtract anything(i.e. daily subtract quantity)
    // in the $activeness value
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
    // note: each comment increases
    // activness of a post by +2
    $activeness += 2;
    // $activeness can never be less than 0
    // so, put a check
    if($activeness < 0) {
        $activeness = 0;
    }

    // ok now update in the db table
    $sql = "UPDATE post SET comment_count = ?, activeness = ?, activeness_update_datetime = ? WHERE id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for updating value of comment_count, activeness, activeness_update_datetime of post table";
    }
    $stmt->bind_param("ssss", $commentCount, $activeness, $commentDatetime, $postID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for updating comment_count activeness, activeness_update_datetime value in post table while adding comment";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible

    // at last close the connection
    $conn->close();

    return $commentID;
}

?>