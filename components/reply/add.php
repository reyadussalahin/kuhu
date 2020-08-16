<?php
// reply table
// "CREATE TABLE IF NOT EXISTS reply (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     comment_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL,
//     datetime DATETIME NOT NULL,
//     reply_at VARCHAR(255) NOT NULL DEFAULT '', -- note: reply_at refers to the parent reply id
//     -- to which this reply is made
//     -- also note: this value will be empty string, if this reply is made to
//     -- a comment
//     vote_count BIGINT NOT NULL DEFAULT 0,
//     reply_reply_count BIGINT NOT NULL DEFAULT 0 -- note: this is the count of replies
//     -- that has been made to this reply i.e. no of child of this replies
// )";

// reply_text table
// "CREATE TABLE IF NOT EXISTS reply_text (
//     id VARCHAR(255) NOT NULL PRIMARY KEY,
//     reply_id VARCHAR(255) NOT NULL,
//     text TEXT NOT NULL DEFAULT '',
//     position INT NOT NULL
// )";

require_once("database/database.php");

require_once("utils/user/currentUser.php");

require_once("utils/time/time.php");
require_once("utils/reply/replyID.php");
require_once("utils/replyText/replyTextID.php");


function addReply($commentID, $replyAt, $replyText) {
    $replyID = ReplyID::get();
    $replyDatetime = Time::bangladeshDateTime();
    $userID = getCurrentUserID();

    $conn = Database::getConnection();

    // inserting values in reply table
    $sql = "INSERT INTO reply (id, comment_id, user_id, datetime, reply_at) VALUES (?, ?, ?, ?, ?)";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for inserting values in reply table";
    }

    $stmt->bind_param("sssss", $replyID, $commentID, $userID, $replyDatetime, $replyAt);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting values in reply table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible


    // ok, so inserting values in reply table is a success
    // now, its time to add values in reply_text table

    // note: we assume to order reply contents in blocks which have positions
    //       like:
    //       block-position-01: contains some text
    //       block-position-02: contains one/two/more image(s)
    //       block-position-03: again some texts
    //       block-position-04: a video/more videos
    //       etc...
    //       each block is distributed in text, image, video etc... tables
    //       but there position is unique when we merge them
    //       *** this block by block reply content management is right now not
    //           implemented. We'd look to it in near future
    //           for simplicity we're now considering only texts

    $sql = "INSERT INTO reply_text (id, reply_id, text, position) VALUES (?, ?, ?, ?)";
    
    // at first, get reply text id
    $replyTextID = ReplyTextID::get();
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for inserting values in reply_text table";
    }

    $defaultPosition = -1; // just for filling the value as "position" column is "NOT NULL"
    
    $stmt->bind_param("sssi", $replyTextID, $replyID, $replyText, $defaultPosition);
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting values in reply_text table";
    }

    ReplyTextID::increment();

    $stmt->close(); // closing $stmt is very important
    
    ReplyID::increment();
    


    // ok, so reply data is added to proper tables

    // now, its time to update comment tables data a bit
    // we've to update repl_count of comment table
    $sql = "UPDATE comment SET reply_count = reply_count + 1 WHERE id = ?";
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for updating reply_count in comment table while adding new reply to database";
    }

    $stmt->bind_param("s", $commentID);

    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for updating reply_count in comment table while adding new reply to database";
    }

    // closing statement
    $stmt->close();

    // at last close the connection
    $conn->close();

    return $replyID;
}

?>