<?php
// this file is for testing postID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "postID.php"); // for testing purposes
require_once("utils/post/postID.php");

for($i=0; $i<100; $i++) {
    $postID = PostID::get();
    echo "post_id: " . $postID . "\n";
    PostID::increment();
}

PostID::reset();

?>