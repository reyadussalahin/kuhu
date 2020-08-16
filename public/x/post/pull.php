<?php

require_once("utils/user/currentUser.php");

require_once("components/post/pull.php");
require_once("components/post/pullByUser.php");
require_once("components/post/pullByUniverse.php");

// this script always sends some json data to client
// so, setting the content-type to json
// and declaring an global array(for this script)
header("Content-Type: application/json");
$response = [];


if($_SERVER["REQUEST_METHOD"] === "GET") {
    $response["request"] = "success";
    
    $posts = [];

    if(isset($_GET["user"])) {
        $user = $_GET["user"];
        // if user's profile page sent the request
        // then, its possible it might not have
        // parameter set
        if($user === "") {
            $user = getCurrentusername();
        }

        $limit = 5; // default
        $offset =  0; // default

        $status = true; // default
        if(!isset($_GET["offset"])) {
            $status = "wrong request format detected. offset is not provided.";
        } else {
            // update offset
            $offset = $_GET["offset"];
            // update status
            $status = pullPostsByUser($posts, $limit, $offset, $user);
        }
        if($status !== true) {
            $response["status"] = "undone";
            $response["message"] = "couldn't retrieve posts from database";
            $response["error"] = $status; // cause in this case $posts contains
            // the error
        } else {
            $response["status"] = "done";
            // $response["posts"] = json_encode($posts);
            $response["posts"] = $posts;
        }

    } else if(isset($_GET["universe"])) {
        $universeName = $_GET["universe"];
        $limit = 5;
        $offset = 0;
        $status = pullPostsByUniverse($posts, $limit, $offset, $universeName);

        // cause it might possible universe name does not exists
        // in this case $status's value would be false
        if($status !== false && $status !== true) {
            $response["status"] = "undone";
            $response["message"] = "couldn't retrieve posts from database";
            $response["error"] = $status; // cause in this case $posts contains
            // the error
        } else {
            $response["status"] = "done";
            // $response["posts"] = json_encode($posts);
            $response["posts"] = $posts;
        }

    } else {
        $limit = 5;
        $offset = 0;
        $status = pullPosts($posts, $limit, $offset);
        if($status !== true) {
            $response["status"] = "undone";
            $response["message"] = "couldn't retrieve posts from database";
            $response["error"] = $status; // cause in this case $posts contains
            // the error
        } else {
            $response["status"] = "done";
            // $response["posts"] = json_encode($posts);
            $response["posts"] = $posts;
        }
    }

} else {

    $response["request"] = "failure";
    
    $response["message"] = "invalid request type";
}

echo json_encode($response);
?>