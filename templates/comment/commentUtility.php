<?php
// this template provides utility for comment
// i.e.
// (1) comment-container(which contains comment cells)
// (2) comment-form

require_once("components/user/authentication.php");

?>

<div class="comment-utility --kuhu-padding-top-6px --kuhu-padding-x-12px --kuhu-border-top-1px --kuhu-border-top-light">
    <?php
        include("templates/comment/commentContainer.php");
    ?>

    <?php
        // if(isAuthenticated()):
            include("templates/comment/commentForm.php");
        // endif;
    ?>
</div>