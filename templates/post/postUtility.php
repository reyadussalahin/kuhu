<?php
// this merges
// (1) templates/post/createPostWrapper.php with
// (2) templates/post/postWrapper.php
// and creates a full template for post

// note: it only shows create-post-wrapper only if user is authenticated

require_once("components/user/authentication.php");
require_once("utils/user/currentUser.php");
?>

<div id="post-utility" class="post-utility --kuhu-box">
    <?php
        if(isAuthenticated()):
            // checking for own profile page or not
            // if username is not set or empty string, then show
            // if username is set and username == getUsername(), then show
            // otherwise, don't show
            if(!isset($username) || $username === "" || $username === getCurrentUsername()):
                include("templates/post/postFormWrapper.php");
            endif;
        endif;
    ?>

    <?php
        include("templates/post/postContainerWrapper.php");
    ?>
</div>