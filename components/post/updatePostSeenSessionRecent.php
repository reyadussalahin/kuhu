<?php

require_once("database/database.php");

require_once("utils/session/currentSession.php");
require_once("utils/time/time.php");


function updatePostSeenSessionRecent(&$posts, $universeID) {
    // if no posts then just return
    $countNewPosts = count($posts);
    if($countNewPosts === 0) return;

    $currentSessionID = getCurrentSessionID();
    $todayDatetime = Time::bangladeshDateTime();


    $conn = Database::getConnection();


    // first check post_seen_session_recent_count table that how many rows
    // post_seen_session_recent table has
    $sql = "SELECT post_seen_count FROM post_seen_session_recent_count WHERE session_id = ? AND universe_id = ?";


    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for retrieving post_seen_count from post_seen_session_recent_count table";
    }

    $stmt->bind_param("ss", $currentSessionID, $universeID);
    $stmt->execute();

    $postSeenCount = ""; // default value
    if(!$stmt->bind_result($postSeenCount)) {
        $stmt->close();
        $conn->close();
        return "couldn't bind results i.e. postSeenCount with statement in post_seen_session_recent_count table";
    }
    // now fetch data
    $fetchStatus = $stmt->fetch();
    $stmt->close(); // it's very important to close statement
    // otherwise, you can not create another statement
    // with the same connection $conn


    // now, its time to insert/update post_seen_session_recent_count table
    if(!$fetchStatus) {
        // i.e. user has no previous record of seeing posts
        // so, we need to insert data into post_seen_session_recent_count_table

        // assign proper value of $postSeenCount
        $postSeenCount = $countNewPosts;
        
        $sql = "INSERT INTO post_seen_session_recent_count (post_seen_count, update_datetime, session_id, universe_id) VALUES (?, ?, ?, ?)";

    } else {
        // so, user has previous record of seeing posts
        
        $postSeenCount = intval($postSeenCount);
        
        // save previous value of $postSeenCount
        $prevPostSeenCount = $postSeenCount;

        // update to proper value of $postSeenCount
        $postSeenCount += $countNewPosts;

        if($postSeenCount >= 4096 && $prevPostSeenCount > 2048) { // if by adding
            // new posts data the no of rows is greater than or equal 4096
            // and $postSeenCount > 2048(otherwise, there's no data to delete)
            // then, keep only 2048
            // and delete others
            $postSeenCount = 2048 + $countNewPosts;
            $sql = "DELETE FROM post_seen_session_recent
                    WHERE session_id = ? AND universe_id = ?
                        AND send_datetime <= (
                            -- note: mysql does not allow to use limit inside subquery
                            --       but a subquery inside a subquery can be materialized, which is
                            --       basically an exception
                            --       see: Exception: The preceding prohibition does not apply if for the modified table you are using a derived table and that derived table is materialized rather than merged into the outer query
                            --       link: https://dev.mysql.com/doc/refman/5.7/en/subquery-restrictions.html
                            --       see the second table and its above description and discussion
                            -- there are other ways to do it, but its the faster method
                            SELECT send_datetime
                            FROM (
                                SELECT send_datetime
                                FROM post_seen_session_recent
                                WHERE session_id = ? AND universe_id = ?
                                ORDER BY send_datetime DESC
                                LIMIT 1 OFFSET 2048
                            ) DERIVED_TABLE -- derived table, materialized temporary table
                        )";
            if(!($stmt = $conn->prepare($sql))) {
                $conn->close();
                return "can't prepare statement for deleting extra(cause, post_seen_count >= 4096) seen posts from post_seen_session_recent table";
            }

            // binding parameters
            $stmt->bind_param("ssss", $currentSessionID, $universeID, $currentSessionID, $universeID);
            
            if(!$stmt->execute()) {
                $stmt->close();
                $conn->close();
                return "couldn't execute statement for deteting extra (cause, post_seen_count >= 4096) seen posts from post_seen_session_recent table";
            }

            // ok, so deleting successful
            $stmt->close(); // closing $stmt is very important
            // if one statement is not close
            // preparing another statement using the same connection
            // is not possible
        }
        // else {
        //     // do nothing
        // }

        // now, declare sql for updating post_seen_count and update_datetime
        $sql = "UPDATE post_seen_session_recent_count SET post_seen_count = ?, update_datetime = ? WHERE session_id = ? AND universe_id = ?";
    }

    // prepare sql
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for inserting/updating data into post_seen_session_recent_count table";
    }

    // binding params
    $stmt->bind_param("isss", $postSeenCount, $todayDatetime, $currentSessionID, $universeID);

    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for inserting/updating data into post_seen_session_recent_count table";
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible



    // now, we've to remember that we've to insert new data inside post_seen_session_recent table
    $sql = "INSERT INTO post_seen_session_recent (post_id, session_id, universe_id, send_datetime) VALUES (?, ?, ?, ?)";
    
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "can't prepare statement for inserting data into post_seen_session_recent table";
    }
    

    // by default
    $postID = "";

    // binding parameters to post_seen_recent table
    $stmt->bind_param("ssss", $postID, $currentSessionID, $universeID, $todayDatetime);

    foreach($posts as &$post) {
        $postID = $post["id"];
        // insert value to post_seen_recent table
        if(!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "couldn't execute statement for inserting values in post_seen_session_recent table";
        }
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible


    $conn->close();
}

?>