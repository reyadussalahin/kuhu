<?php
// post wrapper contains:
// (1) post-container-prefix
// (2) post-container
// (3) and of course also holds unitPost.php - a template which is used for copying
//     and showing many posts created using javascript from json data(send to
//     clientside from server)
?>

<div id="post-container-wrapper" class="--kuhu-min-height-viewport">
    <!-- show posts for user whether user authenticated or not -->
    <!-- note: this is just a template for post, it is used
    in the front-end by javascript to display posts
    posts are send as json data in the frontend -->
    
    <div id="post-container-prefix" class="--kuhu-margin-bottom-10px --kuhu-padding-y-6px --kuhu-border-round-lg-3px --kuhu-text-center --kuhu-font-15px --kuhu-font-700 --kuhu-color-prefix --kuhu-bg-prefix">
        Posts
    </div>
    
    <?php
        include("templates/post/postContainer.php");
    ?>
    
</div>