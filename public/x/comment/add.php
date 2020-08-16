<?php
// this file is the controller for comment form
// i.e. it recieves comment form data and filter and validates
// and then, send it to model/components to insert int the database

require_once("settings/settings.php");

require_once("components/user/authentication.php");

require_once("components/comment/filter.php");
require_once("components/comment/validation.php");
require_once("components/comment/process.php");

require_once("components/comment/add.php");

require_once("utils/user/currentUser.php");
require_once("utils/time/time.php");


$liveRoot = Settings::getLiveRoot();

header("Content-Type: application/json");
$response = [];

if(!isAuthenticated()) {
    $response["request"] = "authentication-error";
    $response["message"] = "please first login to comment";
    $response["error"] = [
        "login-error" => "user not loggedin"
    ];
    echo json_encode($response);
    exit(0);
}

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $response["request"] = "success";

    $postID = $_POST["post-id"];
    $commentText = $_POST["comment-form-text"];
    
    $commentText = filterCommentText($commentText);
    $commentFormTextErrMsg = validateCommentText($commentText);

    // if no errors i.e $commentFormTextErrMsg === "" empty string
    if(!($commentFormTextErrMsg)) {
        $username = getCurrentUsername();
        $datetime = Time::bangladeshDateTime();

        // now add Comment to Database
        $commentID = addComment($postID, $commentText);

        // check if $commentID is an error
        if(strpos($commentID, " ") !== false) {
            $response["status"] = "undone";
            $response["message"] = "couldn't add comment to database";
            $response["error"] = $commentID;
        } else {
            $response["status"] = "done";
            $response["comment"] = [
                "id" => $commentID,
                "by" => $username,
                "post-id" => $postID,
                "text" => $commentText,
                "datetime" => $datetime,
                "vote-count" => 0
            ];
        }
    } else {
        $response["status"] = "error";

        $error = [];
        if($commentFormTextErrMsg !== "") {
            $error["comment-form-text-error-msg"] = $commentFormTextErrMsg;
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