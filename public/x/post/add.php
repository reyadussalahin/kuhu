<?php

require_once("settings/settings.php");

require_once("components/user/authentication.php");

require_once("components/post/validation.php");
require_once("components/post/filter.php");
require_once("components/post/process.php");
require_once("components/post/add.php");

require_once("utils/user/currentUser.php");
require_once("utils/time/time.php");

$liveRoot = Settings::getLiveRoot();

if(!isAuthenticated()) {
    header("Location: $liveRoot/index.php");
    exit(0);
}

$postTitle = "";
$postDescription = "";
$postUniverses = "";

// this script always sends some json data to client
// so, setting the content-type to json
// and declaring an global array(for this script) $status = []
header("Content-Type: application/json");
$response = [];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    // correct request format received
    // on correct correct request it sets "request" to "success"
    // then, it try to determine "status"
    // if there is no validation error, then
    // it sets "status" to done and then
    // returns the processed post in "post" key(json format)
    // if got some validation error, then
    // set "status" to "error"
    // and send errors in "error" key of "status" in json format
    $response["request"] = "success";

    $title = $_POST["post-form-title"];
    $description = $_POST["post-form-description"];
    $universes = $_POST["post-form-universes"];

    // filtering
    $title = filterTitle($title);
    $description = filterDescription($description);
    $universes = filterUniverses($universes);

    // some elements needs to be processed before validation
    // processing universes
    $title = processTitle($title);
    $descriptionBlocks = processDescription($description);
    $universes = processUniverses($universes);

    // first validate post content i.e. post title and post description
    $contentErrorMsg = validateContent($title, $descriptionBlocks);
    $universesErrorMsg = validateUniverses($universes);

    // no need to send all those data back again
    // only status will suffice if success
    // and if error, then we need to send
    // error type with error message
    // the error types are(for now):
    // (1) contentErrorMsg(if both post title and description are null)
    // (2) universesErrorMsg(if any universe name is some vulgar word or so etc...)
    // note: we need to prepare a list of vulgar words, we didn't do it yet
    // so, basically check for type 2 does nothing yet
    // for details see validatePostUniverses() method in "components/post/validation.php"

    if(!($contentErrorMsg || $universesErrorMsg)) {
        // so, no error found during processing
        // or, validation
        // so,
        // we can add the post to database
        // let's add the post to database
        $postID = addPost($title, $descriptionBlocks, $universes);

        // check if $postID is an error or not
        // a proper postID never contains space but an error always
        // contains space
        if(strpos($postID, " ") !== false) {
            $response["status"] = "undone";
            $response["message"] = "couldn't add post to database";
            $response["error"] = $postID;
        } else {
            // i.e. post data inserted to database successfully
            // so, success in everything
            // so, set "status" to "done"
            // and send processed post by setting "post" key
            $response["status"] = "done";
            
            $post = [
                "id" => $postID,
                "by" => getCurrentUsername(),
                "title" => $title,
                "descriptionBlocks" => $descriptionBlocks,
                "universes" => $universes,
                "datetime" => Time::bangladeshDateTime(),
                "comment-count" => 0,
                "vote-count" => 0,
                "share-count" => 0
            ];
            
            // $response["post"] = json_encode($post);
            $response["post"] = $post;
        }
    } else {
        $error = [];
        $response["status"] = "error";
        // found errors
        if($contentErrorMsg !== "") {
            $error["postFormContentErrorMsg"] = $contentErrorMsg;
        }
        if($universesErrorMsg !== "") {
            $error["postFormUniversesErrorMsg"] = $universesErrorMsg;
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