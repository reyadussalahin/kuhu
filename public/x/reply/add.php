<?php
// this file is the controller for reply form
// i.e. it recieves reply form data and filter and validates
// and then, send it to model/components to insert int the database

require_once("settings/settings.php");

require_once("components/user/authentication.php");

require_once("components/reply/filter.php");
require_once("components/reply/validation.php");
require_once("components/reply/process.php");

require_once("components/reply/add.php");

require_once("utils/user/currentUser.php");
require_once("utils/time/time.php");


$liveRoot = Settings::getLiveRoot();

header("Content-Type: application/json");
$response = [];

if(!isAuthenticated()) {
    $response["request"] = "authentication-error";
    $response["message"] = "please first login to reply";
    $response["error"] = [
        "login-error" => "user not loggedin"
    ];
    echo json_encode($response);
    exit(0);
}

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $response["request"] = "success";

    $commentID = $_POST["comment-id"];
    $replyAt = $_POST["reply-at"];
    $replyText = $_POST["reply-form-text"];
    
    if($replyAt === null) {
        $replyAt = "";
    }

    $replyText = filterReplyText($replyText);
    $replyFormTextErrMsg = validateReplyText($replyText);

    if(!($replyFormTextErrMsg)) {
        $username = getCurrentUsername();
        $datetime = Time::bangladeshDateTime();

        // now, add reply to database

        $replyID = addReply($commentID, $replyAt, $replyText);

        // check if $replyID is an error or not
        if(strpos($replyID, " ") !== false) {
            $response["status"] = "undone";
            $response["message"] = "couldn't add reply to database";
            $response["error"] = $replyID;
        } else {
            $response["status"] = "done";
            $response["reply"] = [
                "id" => $replyID,
                "by" => $username,
                "comment-id" => $commentID,
                "text" => $replyText,
                "datetime" => $datetime,
                "vote-count" => 0,
                "reply-at" => $replyAt
            ];
        }
    } else {
        $response["status"] = "error";

        $error = [];
        if($replyFormTextErrMsg !== "") {
            $error["reply-form-text-error-msg"] = $replyFormTextErrMsg;
        }

        $response["error"] = $error;
    }
} else {
    // invalid request received
    // on invalid request "request" is set to "failure"
    $response = [
        "request" => "failure",
        "message" => "invalid request type detected"
    ];
}

// returning content of $response
echo json_encode($response);

?>