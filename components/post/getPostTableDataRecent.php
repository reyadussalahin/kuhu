<?php
// this function returns post table data

// "post" table
// "CREATE TABLE IF NOT EXISTS post (
//     id VARCHAR(255) PRIMARY KEY,
//     user_id VARCHAR(255),
//     title VARCHAR(255),
//     post_time DATETIME
// )";


require_once("components/post/loadPostTableDataRecentGlobal.php");
require_once("components/post/loadPostTableDataRecentUniverse.php");


function getPostTableDataRecent(&$posts, $limit, $offset, $universeID) {
    $loadPostStatus = true;
    if($universeID === "") {
        $loadPostStatus = loadPostTableDataRecentGlobal($posts, $limit, $offset);
    } else {
        $loadPostStatus = loadPostTableDataRecentUniverse($posts, $limit, $offset, $universeID);
    }
    
    // if $loadPostStatus is an error
    if($loadPostStatus !== true) {
        return $loadPostStatus;
    }

    // if $posts type has changed unexpectedly
    if(gettype($posts) !== "array") {
        return  $posts;
    }

    // if no posts is loaded
    if(count($posts) === 0) {
        // $stmt->close();
        // $conn->close();
        // return "post data not found while searching post data in post table";
        // this is only possible when the website just started
        // so, do nothing
        
        // don't mind the previous comment
        // now, it means user has finished seeing
        // all the posts has been done in kuhu
        // so, reset the post_seen_user_recent and post_seen_user_recent table
        // or, if not authenticated
        // then, reset the post_seen_session_recent and post_seen_session_recent table
        $postSeenRecentResetStatus = true;
        if(!isAuthenticated()) {
            $postSeenRecentResetStatus = resetPostSeenSessionRecent($universeID);
        } else {
            $postSeenRecentResetStatus = resetPostSeenUserRecent($universeID);
        }
        // check if postSeenUserRecentResetStatus
        // is an error or not
        if($postSeenRecentResetStatus !== true) {
            return $postSeenRecentResetStatus;
        }

        // now try again to load posts
        if($universeID === "") {
            $loadPostStatus = loadPostTableDataRecentGlobal($posts, $limit, $offset);
        } else {
            $loadPostStatus = loadPostTableDataRecentUniverse($posts, $limit, $offset, $universeID);
        }
        // check if $loadPostStatus is an error
        if($loadPostStatus !== true) {
            return $loadPostStatus;
        }

        // even after that count($posts) is 0, then kuhu is in
        // initial state, no posts has been made yet
        if(count($posts) === 0) {
            // then, it is initial state
            // do nothing
            // return "initial state";
        }
    }

    if(!isAuthenticated()) {
        // we'll write session recent here
        updatePostSeenSessionRecent($posts, $universeID);
    } else {
        updatePostSeenUserRecent($posts, $universeID);
    }

    return true;
}

?>