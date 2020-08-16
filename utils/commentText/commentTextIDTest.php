<?php
// this file is for testing commentTextID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "commentTextID.php"); // for testing purposes
require_once("utils/commentText/commentTextID.php");

for($i=0; $i<100; $i++) {
    $commentTextID = CommentTextID::get();
    echo "comment-text-id: " . $commentTextID . "\n";
    CommentTextID::increment();
}

CommentTextID::reset();

?>