<?php
// this file is used to retrieve post

require_once("components/post/getPostTableDataRecent.php");
require_once("components/post/getPostTextTableData.php");
require_once("components/post/getPostUniverseTableData.php");
require_once("components/post/getPostVoteTableData.php");


function pullPosts(&$posts, $limit, $offset) {
    // note: the global universe id refers to all posts
    //       of all universes, i.e. all present posts in
    //       kuhu database
    $globalUniverseID = "";
    $status = getPostTableDataRecent($posts, $limit, $offset, $globalUniverseID);
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