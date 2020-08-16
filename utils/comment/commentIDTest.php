<?php
// this file is for testing commentID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "commentID.php"); // for testing purposes
require_once("utils/comment/commentID.php");

for($i=0; $i<100; $i++) {
    $commentID = CommentID::get();
    echo "comment_id: " . $commentID . "\n";
    CommentID::increment();
}

CommentID::reset();

?>