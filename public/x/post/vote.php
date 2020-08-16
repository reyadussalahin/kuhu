<?php
// this file is controller for vote

require_once("settings/settings.php");
require_once("components/user/authentication.php");

require_once("components/post/vote.php");

$liveRoot = Settings::getLiveRoot();

header("Content-Type: application/json");

$response = [];


if(!isAuthenticated()) {
    $response["request"] = "authentication-error";
    $response["message"] = "please first login to upvote";
    $response["error"] = [
        "login-error" => "user not loggedin"
    ];
    echo json_encode($response);
    exit(0);
}


if($_SERVER["REQUEST_METHOD"] === "GET") {
    $response["request"] = "success";

    if(isset($_GET["vote"])) {
        $vote = $_GET["vote"];
        $postID = $_GET["post-id"];
        $response["status"] = "success";

        if($vote === "u") {
            $voteCnt = votePost($postID, 1);
            $response["vote"] = [
                "vote-count" => $voteCnt,
                "received-vote" => "u",
                "post-id" => $postID
            ];
        } else {
            $voteCnt = votePost($postID, -1);
            $response["vote"] = [
                "vote-count" => $voteCnt,
                "received-vote" => "d",
                "post-id" => $postID
            ];
        }
    } else {
        $response["status"] = "error";
        $response["message"] = "vote value not provided";
        $response["error"] = [
            "invalid-vote-type" => "provide a value to indicate vote type"
        ];
    }
} else {
    $response = [
        "request" => "failure",
        "message" => "invalid request type"
    ];
}

echo json_encode($response);

?>