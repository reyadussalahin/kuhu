<?php


require_once("database/database.php");


require_once("components/universe/getName.php");


// "post_universe" table
// "CREATE TABLE IF NOT EXISTS post_universe (
//     post_id VARCHAR(255),
//     universe_id VARCHAR(255)
// )";


function getPostUniverseTableData(&$posts) {
    $conn = Database::getConnection();
    
    // okay, so far we've been able to retrieve the descriptionBlocks
    // now, let's try for universeIDs associated with this post

    $sql = "SELECT universe_id FROM post_universe WHERE post_id = ?";

    // notice: we've taken $post reference i.e. &$post,
    //         so that, any change done to a single $post
    //         is captured in $posts array(the array that
    //         contains all posts)
    foreach($posts as &$post) {

        $postID = $post["id"];

        if(!($stmt = $conn->prepare($sql))) {
            $conn->close();
            return "statement preparation for retrieving uuniverseID using postID has failed";
        }

        $stmt->bind_param("s", $postID);
        $stmt->execute();
        
        $universes = []; // by default no posts

        $universeID = ""; // by default
        if(!$stmt->bind_result($universeID)) {
            $stmt->close();
            $conn->close();
            return "parameter binding for retrieving universeIDs from post_universe table has failed";
        }
        
        // $universeID gets updated while $stmt->fetch() is called
        while($stmt->fetch()) {
            $universeName = getUniverseName($universeID);
            // check if uniVerseName is good
            // note: universeName can not contain any whitespace
            // so, check
            if(strpos($universeName, " ") !== false) {
                return $universeName; // cause $universeName actually contains error
            }

            // store $universeName in $universes array
            $universes[] = $universeName;
        }
        
        if(count($universes) === 0) {
            // $stmt->close();
            // $conn->close();
            // return "post data not found while searching post data in post table";

            // this time we're just ignoring it
            // cause it possible no universes is provided in this post
            // so, this post is only going to be shared in users profile
            // or, we may take some other decision for this type of post later
        }

        $post["universes"] = $universes;

        $stmt->close(); // closing this statement is very important
        // otherwise, you can not create another statement with the same connection $conn
    }

    // okay, so we've also been able to retrieve universes data successfully

    $conn->close();

    return true;
}

?>