<?php
// this file is for testing replyID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "replyID.php"); // for testing purposes
require_once("utils/reply/replyID.php");

for($i=0; $i<100; $i++) {
    $replyID = ReplyID::get();
    echo "reply_id: " . $replyID . "\n";
    ReplyID::increment();
}

ReplyID::reset();

?>