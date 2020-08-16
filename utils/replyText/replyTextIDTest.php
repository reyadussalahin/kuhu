<?php
// this file is for testing replyTextID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "replyTextID.php"); // for testing purposes
require_once("utils/replyText/replyTextID.php");

for($i=0; $i<100; $i++) {
    $replyTextID = ReplyTextID::get();
    echo "reply-text-id: " . $replyTextID . "\n";
    ReplyTextID::increment();
}

ReplyTextID::reset();

?>