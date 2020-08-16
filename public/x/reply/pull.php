<?php

require_once("components/reply/pull.php");

// this script always sends some json data to client
// so, setting the content-type to json
// and declaring an global array(for this script)
header("Content-Type: application/json");
$response = [];


if($_SERVER["REQUEST_METHOD"] === "GET") {
    $response["request"] = "success";
    
    if(isset($_GET["comment-id"]) && isset($_GET["offset"])) {
        $commentID = $_GET["comment-id"];
        $offset = $_GET["offset"];
        
        $offset = intval($offset);

        $limit = 5; // by default
        // but if offset is 0, then just 2
        if($offset === 0) {
            $limit = 2;
        }

        $replies = [];

        $status = pullReplies($replies, $commentID, $limit, $offset);

        // check if error
        if($status !== true) {
            $response["status"] = "undone";
            
            $response["message"] = "couldn't load data from database";
            
            $response["error"] = [
                "database-error" => $status
            ];

        } else {
            $response["status"] = "done";
            
            $response["replies"] = $replies;
        }

    } else {
        $response["status"] = "undone";
        $response["message"] = "request didn't come with proper url formatting";
        $response["error"] = [
            "url-format-error" => "url parameter is not set properly"
        ];
    }

} else {
    $response["request"] = "failure";
    
    $response["message"] = "invalid request type";
}

echo json_encode($response);

?>