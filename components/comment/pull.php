<?php

require_once("components/comment/loadCommentTableDataRequested.php");
require_once("components/comment/getCommentTextTableData.php");
require_once("components/comment/getCommentSelfVoteStatus.php");
require_once("components/comment/loadRepliesFromReplyComponent.php");


function pullComments(&$comments, $postID, $limit, $offset) {
    $status = loadCommentTableDataRequested($comments, $postID, $limit, $offset);
    if($status !== true) {
        return $status;
    }

    $status = getCommentTextTableData($comments);
    if($status !== true) {
        return $status;
    }

    $status = getCommentSelfVoteStatus($comments);
    if($status !== true) {
        return $status;
    }

    $status = loadRepliesFromReplyComponent($comments);
    if($status !== true) {
        return $status;
    }

    return true;
}

?>