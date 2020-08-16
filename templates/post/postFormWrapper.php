<?php
// it wraps post-form
// (note: we're using "post-form-wrapper" to wrap the prefix(which says "Create Post") and html form)
?>

<div id="post-form-wrapper" class="--kuhu-box --kuhu-margin-bottom-12px --kuhu-padding-bottom-10px --kuhu-bg-white --kuhu-border-lg-1px --kuhu-border-round-lg-3px --kuhu-border-light">
    
    <div id="post-form-prefix" class="--kuhu-padding-x-12px --kuhu-padding-y-8px    --kuhu-border-top-round-lg-3px --kuhu-border-bottom-solid --kuhu-border-bottom-1px --kuhu-border-bottom-light --kuhu-bg-prefix">
        <div class="--kuhu-visual --kuhu-font-13px --kuhu-font-600 --kuhu-color-prefix">
            Create Post
        </div>
    </div>
    
    <div id="post-form-container" class="--kuhu-box --kuhu-padding-x-12px">
        <?php
            include("templates/post/postForm.php");
        ?>
    </div>

</div>