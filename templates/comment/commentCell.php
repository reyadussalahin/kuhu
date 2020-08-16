<?php
// comment cell contains
// (1) comment-data
// (2) reply-utility(which contains reply-cell and reply-form)
?>

<div id="comment-cell-template" class="comment-cell">
    <?php
        include("templates/comment/commentData.php");
    ?>

    <?php
        include("templates/reply/replyUtility.php");
    ?>
</div>