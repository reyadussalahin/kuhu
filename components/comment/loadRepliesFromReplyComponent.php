<?php

require_once("components/reply/pull.php");


function loadRepliesFromReplyComponent(&$comments) {

    // when first loading replies
    // we'd use this parameters
    $limit = 1;
    $offset = 0;

    foreach($comments as &$comment) {
        $comment["replies"] = [];

        $status = pullReplies($comment["replies"], $comment["id"], $limit, $offset);
        if($status !== true) {
            return $status;
        }
    }

    return true;
}

?>