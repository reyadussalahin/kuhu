<?php
// this is the template of a unit post
// i.e. it contains html of how a unit post would contain
// it does not contain any content, just html elements/tags
// it is copied again and again to display new post in client
// pc/browser
?>

<div id="post-cell-template" class="post-cell --kuhu-margin-bottom-12px --kuhu-padding-bottom-6px --kuhu-border-lg-1px --kuhu-border-round-lg-3px --kuhu-border-light --kuhu-bg-white">
    <?php
        include("templates/post/postData.php");
    ?>

    <?php
        include("templates/comment/commentUtility.php");
    ?>
</div>