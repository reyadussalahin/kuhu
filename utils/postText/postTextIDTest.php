<?php
// this file is for testing postTextID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "postTextID.php"); // for testing purposes
require_once("utils/postText/postTextID.php");

for($i=0; $i<100; $i++) {
    $postTextID = postTextID::get();
    echo "post-text-id: " . $postTextID . "\n";
    PostTextID::increment();
}

postTextID::reset();

?>