<?php

require_once("database/database.php");

require_once("components/user/authentication.php");
require_once("components/user/getUsername.php");

require_once("components/post/updatePostSeenUserRecent.php");
require_once("components/post/resetPostSeenUserRecent.php");

require_once("components/post/updatePostSeenSessionRecent.php");
require_once("components/post/resetPostSeenSessionRecent.php");

require_once("utils/user/currentUser.php");
require_once("utils/session/currentSession.php");


function loadPostTableDataRecentGlobal(&$posts, $limit, $offset) {
    $conn = Database::getConnection();

    if(!isAuthenticated()) {
        // if user not authenticated
        // then, you don't need to filter what post he/she
        // has seen or not
        // we'll use session filtering
        // for that we've a table called post_seen_session_recent
        // note: when isAuthenticated() function is called
        //       session_start() is also called
        //       so, no need to check if(isset($_SESSION)) and call session_start()
        //       function
        $sql = "SELECT id, user_id, title, datetime, vote_count, comment_count, share_count
                FROM post
                -- note: mysql does not allow to use limit inside subquery
                --       but a subquery inside a subquery can be materialized, which is
                --       basically an exception
                --       see: Exception: The preceding prohibition does not apply if for the modified table you are using a derived table and that derived table is materialized rather than merged into the outer query
                --       link: https://dev.mysql.com/doc/refman/5.7/en/subquery-restrictions.html
                --       see the second table and its above description and discussion
                -- there are other ways to do it, but its the faster method
                WHERE id NOT IN (
                    SELECT post_id
                    FROM post_seen_session_recent
                    WHERE session_id = ? AND universe_id = '')
                ORDER BY activeness DESC
                LIMIT ? OFFSET ?";
    } else {
        $sql = "SELECT id, user_id, title, datetime, vote_count, comment_count, share_count
                FROM post
                WHERE id NOT IN (
                    SELECT post_id
                    FROM post_seen_user_recent
                    WHERE user_id = ? AND universe_id = '')
                ORDER BY activeness DESC
                LIMIT ? OFFSET ?";
    }

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving post table data(recent) failed";
    }

    if(!isAuthenticated()) {
        // if user not authenticated
        $currentSessionID = getCurrentSessionID();
        $stmt->bind_param("sii", $currentSessionID, $limit, $offset);
    } else {
        $currentUserID = getCurrentUserID();
        $stmt->bind_param("sii", $currentUserID, $limit, $offset);
    }
    $stmt->execute();
    

    $posts = []; // by default no posts

    $postID = ""; // by default
    $userID = ""; // by default
    $postTitle = ""; // by default
    $postDatetime = ""; // by default
    $voteCount = "";
    $commentCount = "";
    $shareCount = "";
    
    if(!$stmt->bind_result($postID, $userID, $postTitle, $postDatetime, $voteCount, $commentCount, $shareCount)) {
        $stmt->close();
        $conn->close();
        return "parameter binding for retrieving post data from post table failed";
    }

    // $postID, $userID...etc.. gets updated while $stmt->fetch() is called
    while($stmt->fetch()) {
        $username = getUsername($userID);
        // check if username is good
        // note: username can not contain any whitespace
        // so, check
        if(strpos($username, " ") !== false) {
            $stmt->close();
            $conn->close();
            return $username; // cause $username actually contains error
        }

        // store post in $posts array
        $posts[] = [
            "id" => $postID,
            "by" => $username,
            "title" => $postTitle,
            "datetime" => $postDatetime,
            "comment-count" => $commentCount,
            "vote-count" => $voteCount,
            "share-count" => $shareCount,
        ];
    }

    $stmt->close(); // closing $stmt is very important
    // if one statement is not close
    // preparing another statement using the same connection
    // is not possible

    $conn->close();
    
    return true;
}

?>