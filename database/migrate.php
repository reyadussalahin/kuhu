<?php

// the goal of migrate.php is to create all necessary tables with proper formats

// There's a lot of tables. When creating each table, I've provided full description for that table
// You can update the table structure as your project need if you think you need somethng more in your project

require_once("database/database.php");

if(Database::create()) {
    echo "database \"kuhu\" created successfully\n";
} else {
    echo "database \"kuhu\" can't be created\n";
    echo "it is either a connection problem(check your username, password)\n";
    echo "or, a database named kuhu exists already\n";
    exit(1);
}

// creating connection to database
$conn = Database::getConnection();


// note: we're going to create each database manually
//       so, that we may write each of the contraint for each
//       column accurately

// $tableSqlArray = [];

/**
 * user: id, first_name, last_name, username, email, password, register_time, last_login
 */

$tableSqlArray["user"] = "CREATE TABLE IF NOT EXISTS user (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL DEFAULT '',
    last_name VARCHAR(255) NOT NULL DEFAULT '',
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    register_datetime DATETIME NOT NULL,
    last_login DATETIME NOT NULL
)";

/**
 * user_follow: user_id, follower_id
 */

$tableSqlArray["user_follow"] = "CREATE TABLE IF NOT EXISTS user_follow (
    user_id VARCHAR(255) NOT NULL,
    follower_id VARCHAR(255) NOT NULL
)";

/**
 * universe_follow: user_id, universe_id
 */

$tableSqlArray["universe_follow"] = "CREATE TABLE IF NOT EXISTS universe_follow (
    user_id VARCHAR(255) NOT NULL,
    universe_id VARCHAR(255) NOT NULL
)";

/**
 * universe: id, name
 */

$tableSqlArray["universe"] = "CREATE TABLE IF NOT EXISTS universe (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)";

/**
 * post: id, user_id, title, post_time, vote_count, comment_count, share_count
 */

$tableSqlArray["post"] = "CREATE TABLE IF NOT EXISTS post (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    title TEXT NOT NULL DEFAULT '', -- note: default empty string
    datetime DATETIME NOT NULL,
    vote_count BIGINT NOT NULL DEFAULT 0,
    comment_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    share_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
    activeness BIGINT UNSIGNED NOT NULL DEFAULT 10000,
    -- note: when a post is done, we'd give it an activeness of 10K
    -- then, for a upvote it'll get +1 and for a downvote it'll get -1
    --       for a comment it'll get +2
    -- activeness will be lost subtracted by a specific number everyday
    -- the lowest possible value activeness will get is 0
    -- if activeness is between 0...9, we'll subtract 1 each day
    -- if activeness is between 10...99, we'll subtract 10 each day
    -- if activeness is between 100...999, we'll subtract 100 each day
    -- if activeness is between 1000....9999, we'll subtract 1000 each day
    -- if activeness is between 10000....99999, we'll subtract 10000 each day
    -- and so on
    -- another note is: if by subtraction a number's become less than the
    -- lowest value, then we'll assign it the greatest value of previous
    -- activeness segment. An example would be:
    -- let the present activeness is 10234, the we'll subtract 10000
    -- so, after subtraction it'll be 234, but 234 < 10000,
    -- so, we'll assign it 9999, not 234 i.e. the greatest value of previous
    -- activeness segment
    activeness_update_datetime DATETIME NOT NULL
)";


/**
 * post_seen_recent: post_id, user_id, datetime
 *
 *  note: this table is used for tracking what posts user has been seen till now
 *        and the send_datetime field here refers to the time when
 *        these posts has been sent to user
 */

$tableSqlArray["post_seen_user_recent"] = "CREATE TABLE IF NOT EXISTS post_seen_user_recent (
    post_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    universe_id VARCHAR(255) NOT NULL DEFAULT '', -- note: '' i.e. empty string means global
    -- i.e. empty string refers to posts from all universes
    send_datetime DATETIME NOT NULL
)";

/**
 * post_seen_user_recent_count: user_id, post_seen_count
 * 
 * note: this is to save the count how much posts a user has seen
 *       if post_seen_count reaches/exceeds 4096, we'd update it to 2048
 *       and delete the older 2048 posts entry from post_seen_user_recent
 *       table and set the counter to 2048
 */

$tableSqlArray["post_seen_user_recent_count"] = "CREATE TABLE IF NOT EXISTS post_seen_user_recent_count (
    post_seen_count INT UNSIGNED NOT NULL DEFAULT 0,
    update_datetime DATETIME NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    universe_id VARCHAR(255) NOT NULL DEFAULT '' -- note: '' i.e. empty string means global
    -- i.e. empty string refers to posts from all universes
)";


/**
 * post_seen_session_recent: post_id, session_id, datetime
 *
 *  note: this table is used for tracking what posts has been seen till now
 *        by  anonymous user i.e. unauthenticated user 
 *        and the send_datetime field here refers to the time when
 *        these posts has been sent to user 
 */

$tableSqlArray["post_seen_session_recent"] = "CREATE TABLE IF NOT EXISTS post_seen_session_recent (
    post_id VARCHAR(255) NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    universe_id VARCHAR(255) NOT NULL DEFAULT '', -- note: '' i.e. empty string means global
    -- i.e. empty string refers to posts from all universes
    send_datetime DATETIME NOT NULL
)";

/**
 * post_seen_session_recent_count: session_id, post_seen_count
 * 
 * note: this is to save the count how much posts a session has seen
 *       if post_seen_count reaches/exceeds 4096, we'd update it to 2048
 *       and delete the older 2048 posts entry from post_seen_session_recent
 *       table and set the counter to 2048
 */

$tableSqlArray["post_seen_session_recent_count"] = "CREATE TABLE IF NOT EXISTS post_seen_session_recent_count (
    post_seen_count INT UNSIGNED NOT NULL DEFAULT 0,
    update_datetime DATETIME NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    universe_id VARCHAR(255) NOT NULL DEFAULT '' -- note: '' i.e. empty string means global
    -- i.e. empty string refers to posts from all universes
)";


/**
 * post_share: post_id, user_id, share_time
 */

$tableSqlArray["post_share"] = "CREATE TABLE IF NOT EXISTS post_share (
    post_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    share_datetime DATETIME NOT NULL
)";

/**
 * post_universe: post_id, universe_id
 */

$tableSqlArray["post_universe"] = "CREATE TABLE IF NOT EXISTS post_universe (
    post_id VARCHAR(255) NOT NULL,
    universe_id VARCHAR(255) NOT NULL
)";

/**
 * post_vote: vote, post_id, user_id
 * (vote field can have three values)
 * (+1 means upvote)
 * (-1 means downvote)
 * (0 means, first provided upvote or downvote, but later the opposite)
 */

$tableSqlArray["post_vote"] = "CREATE TABLE IF NOT EXISTS post_vote (
    vote TINYINT NOT NULL DEFAULT 0,
    post_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL
)";

/**
 * post_text: id, post_id, text
 * note: "TEXT" type in mysql holds at most 65,535 bytes
 *       for larger string use "MEDIUMTEXT"(at most 16,777,215 bytes)
 *       the largest is "LONGTEXT"(at most 4GB)
 *       
 *       and about VARCHAR(width):
 *       A commonly used string type. Stores variable-length strings (such as names, addresses, or *       cities) up to a maximum width . The maximum value of width is 65,535 characters.
 *
 */

$tableSqlArray["post_text"] = "CREATE TABLE IF NOT EXISTS post_text (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    post_id VARCHAR(255) NOT NULL,
    text TEXT NOT NULL DEFAULT '',
    position INT UNSIGNED NOT NULL
)";

/**
 * post_image: id, post_id, path, name
 * (here name means image file name while uploading)
 * (here path means, the path in the server to find the image)
 */

$tableSqlArray["post_image"] = "CREATE TABLE IF NOT EXISTS post_image (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    post_id VARCHAR(255) NOT NULL,
    path VARCHAR(2047) NOT NULL,
    name VARCHAR(1023) NOT NULL,
    position INT UNSIGNED NOT NULL
)";

/**
 * post_video: id, post_id, path, name
 * (here name means video file name which found while uploading)
 * (here path means, the path in the server to find the video)
 */

$tableSqlArray["post_video"] = "CREATE TABLE IF NOT EXISTS post_video (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    post_id VARCHAR(255) NOT NULL,
    path VARCHAR(2047) NOT NULL,
    name VARCHAR(1023) NOT NULL,
    position INT UNSIGNED NOT NULL
)";

/**
 * post_file: id, post_id, path, name
 * (this is for pdf, docx etc. file formats)
 * (here name means file name while uploading)
 * (here path means, the path in the server to find the file)
 */

$tableSqlArray["post_file"] = "CREATE TABLE IF NOT EXISTS post_file (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    post_id VARCHAR(255) NOT NULL,
    path VARCHAR(2047) NOT NULL,
    name VARCHAR(1023) NOT NULL,
    position INT UNSIGNED NOT NULL
)";

/**
 * comment: id, post_id, user_id, comment_time, vote_count
 * (comment is done on a post, so, we need to track the post_id)
 */

$tableSqlArray["comment"] = "CREATE TABLE IF NOT EXISTS comment (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    post_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    datetime DATETIME NOT NULL,
    vote_count BIGINT NOT NULL DEFAULT 0,
    reply_count BIGINT UNSIGNED NOT NULL DEFAULT 0
)";

/**
 * comment_vote: comment_id, vote, user_id
 * (vote field can have three values)
 * (+1 means upvote)
 * (-1 means downvote)
 * (0 means, first provided either upvote or downvote, but later the opposite)
 */

$tableSqlArray["comment_vote"] = "CREATE TABLE IF NOT EXISTS comment_vote (
    vote TINYINT NOT NULL DEFAULT 0,
    comment_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL
)";

/**
 * comment_text: id, comment_id, text
 */

$tableSqlArray["comment_text"] = "CREATE TABLE IF NOT EXISTS comment_text (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    comment_id VARCHAR(255) NOT NULL,
    text TEXT NOT NULL DEFAULT '',
    position INT UNSIGNED NOT NULL
)";

/**
 * comment_image: id, comment_id, path, name
 * (here name means image file name while uploading)
 * (here path means, the path in the server to find the image)
 */

$tableSqlArray["comment_image"] = "CREATE TABLE IF NOT EXISTS comment_image (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    comment_id VARCHAR(255) NOT NULL,
    path VARCHAR(2047) NOT NULL,
    name VARCHAR(1023) NOT NULL,
    position INT UNSIGNED NOT NULL
)";

// [N.B.]: later we'll introduce video and files to comment, if needed !!!

/**
 * reply: id, comment_id, user_id, reply_time
 * (reply is provided of a comment, so, we need to track comment_id)
 */

$tableSqlArray["reply"] = "CREATE TABLE IF NOT EXISTS reply (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    comment_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    datetime DATETIME NOT NULL,
    reply_at VARCHAR(255) NOT NULL DEFAULT '', -- note: reply_at refers to the parent reply id
    -- to which this reply is made
    -- also note: this value will be null, if this reply is made to
    -- a comment
    vote_count BIGINT NOT NULL DEFAULT 0,
    reply_reply_count BIGINT UNSIGNED NOT NULL DEFAULT 0 -- note: this is the count of replies
    -- that has been made to this reply i.e. no of child of this replies
)";

/**
 * reply_vote: reply_id, vote, user_id
 * (vote field can have three values)
 * (+1 means upvote)
 * (-1 means downvote)
 * (0 means, first provided upvote or downvote, but later the opposite)
 */

$tableSqlArray["reply_vote"] = "CREATE TABLE IF NOT EXISTS reply_vote (
    vote TINYINT NOT NULL,
    reply_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL
)";

/**
 * reply_text: id, reply_id, text
 */

$tableSqlArray["reply_text"] = "CREATE TABLE IF NOT EXISTS reply_text (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    reply_id VARCHAR(255) NOT NULL,
    text TEXT NOT NULL DEFAULT '',
    position INT UNSIGNED NOT NULL
)";

/**
 * reply_image: id, reply_id, path, name
 * (here name means image file name while uploading)
 * (here path means, the path in the server to find the image)
 */

$tableSqlArray["reply_image"] = "CREATE TABLE IF NOT EXISTS reply_image (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    reply_id VARCHAR(255) NOT NULL,
    path VARCHAR(2047) NOT NULL,
    name VARCHAR(1023) NOT NULL,
    position INT UNSIGNED NOT NULL
)";

// [N.B.]: later we'll introduce video and files to both comment and reply, if needed !!!



/**
 * creating tables using the sql declared above
 */

foreach($tableSqlArray as $tableName => $sql) {
    if($stmt = $conn->prepare($sql)) {
        // execute statement
        if(!$stmt->execute()) {
            echo "couldn't create table \"$tableName\"\n";
            $stmt->close();
            exit(1);
        }
        $stmt->close();
    } else {
        echo "couldn't create statement for creating \"$tableName\" table\n";
        echo "statement is: \"$sql\"";
        exit(1);
    }
    echo "created \"$tableName\" successfully\n";
}

?>