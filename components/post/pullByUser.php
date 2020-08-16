<?php
// this function returns data posted by a user


require_once("components/post/getPostTableDataByUser.php");
require_once("components/post/getPostTextTableData.php");
require_once("components/post/getPostUniverseTableData.php");

function pullPostsByUser(&$posts, $limit, $offset, $username) {
    $status = getPostTableDataByUser($posts, $limit, $offset, $username);
    if($status !== true) {
        return $status;
    }
    
    $status = getPostTextTableData($posts);
    if($status !== true) {
        return $status;
    }

    $status = getPostUniverseTableData($posts);
    if($status !== true) {
        return $status;
    }

    // this updates if the current user has voted or not
    // i.e. it checks and updates status of self-vote
    //      in pulling posts
    $status = getPostVoteTableData($posts);
    if($status !== true) {
        return $status;
    }

    return true;
}

?>