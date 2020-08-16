<?php

require_once("database/database.php");

require_once("utils/session/currentSession.php");


function resetPostSeenSessionRecent($universeID) {
    $conn = Database::getConnection();

    $currentSessionID = getCurrentSessionID();
    
    $sql = "DELETE FROM post_seen_session_recent WHERE session_id = ? AND universe_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for deleting data to reset post_seen_session_recent table";
    }

    // binding parameter
    $stmt->bind_param("ss", $currentSessionID, $universeID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement to delete data to reset post_seen_session_recent table";
    }
    // so deleting is successful
    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible



    // ok now reset post_seen_session_recent_count
    $sql = "DELETE FROM post_seen_session_recent_count WHERE session_id = ? AND universe_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for deleting data to reset post_seen_session_recent_count table";
    }

    // binding parameter
    $stmt->bind_param("ss", $currentSessionID, $universeID);
    
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement to delete data to reset post_seen_session_recent_count table";
    }
    // ok, so deleting successful
    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible

    // closing connection
    $conn->close();

    return true;
}

?>