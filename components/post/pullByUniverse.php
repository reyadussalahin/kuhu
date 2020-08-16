<?php

// require_once("components/post/getPostUniverseTableDataByUniverse.php");
require_once("components/post/getPostTableDataRecent.php");
require_once("components/post/getPostTableData.php");
require_once("components/post/getPostTextTableData.php");
require_once("components/post/getPostUniverseTableData.php");

require_once("components/universe/getIDWithoutAdding.php");

function pullPostsByUniverse(&$posts, $limit, $offset, $universe) {
    // $status = getPostUniverseTableByUniverse($posts, $universe);
    
    $universeID = getUniverseIDWithoutAdding($universe);
    // in case $universeID not found
    if($universeID === false) {
        return false; // $universeID not found
    }
    // check for error
    // $universeID would never have space
    // unless it is an error
    if(strpos($universeID, " ") !== false) {
        return $universeID; // cause it is an error
    }

    // pull posts with high activeness of provided universe
    $status = getPostTableDataRecent($posts, $limit, $offset, $universeID);
    // // if $universe does not exist
    // if($status === false) {
    //     // that would mean $universe not found
    //     return true;
    // }

    // if error occurs
    if($status !== true) {
        return $status;
    }

    // // no need of it now
    // $status = getPostTableData($posts);
    // // if error occurs
    // if($status !== true) {
    //     return $status;
    // }

    $status = getPostTextTableData($posts);
    // if error occurs
    if($status !== true) {
        return $status;
    }

    $status = getPostUniverseTableData($posts);
    // if error occurs
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