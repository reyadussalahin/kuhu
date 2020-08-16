<?php

require_once("components/reply/loadReplyTableDataRequested.php");
require_once("components/reply/getReplyTextTableData.php");
require_once("components/reply/getReplySelfVoteStatus.php");


function pullReplies(&$replies, $commentID, $limit, $offset) {
    $status = loadReplyTableDataRequested($replies, $commentID, $limit, $offset);
    if($status !== true) {
        return $status;
    }

    $status = getReplyTextTableData($replies);
    if($status !== true) {
        return $status;
    }

    $status = getReplySelfVoteStatus($replies);
    if($status !== true) {
        return $status;
    }

    return true;
}

?>