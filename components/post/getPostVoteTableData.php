<?php


// post_vote table
//
// "CREATE TABLE IF NOT EXISTS post_vote (
//     vote TINYINT NOT NULL DEFAULT 0,
//     post_id VARCHAR(255) NOT NULL,
//     user_id VARCHAR(255) NOT NULL
// )";
//


require_once("database/database.php");

require_once("components/user/authentication.php");

require_once("utils/user/currentUser.php");


function getPostVoteTableData(&$posts) {
    if(!isAuthenticated()) {
        return true;
    }

    $conn = Database::getConnection();

    $currentUserID = getCurrentUserID();
    

    $sql = "SELECT vote FROM post_vote WHERE user_id = ? AND post_id = ?";

    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "statement preparation for retrieving post self vote status using post id and user id has failed";
    }

    // by default
    $postID = "";

    $stmt->bind_param("ss", $currentUserID, $postID);

    foreach($posts as &$post) {
        $postID = $post["id"];

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
                $post["self-vote"] = "u";
            } else if($vote === -1) {
                $post["self-vote"] = "d";
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