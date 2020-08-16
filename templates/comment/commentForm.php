<?php
// comment form
// i.e. the form which says "write a comment"
?>

<form action="<?= $liveRoot; ?>/x/comment/add.php" method="POST" class="comment-form --kuhu-display-flex --kuhu-align-items-center --kuhu-padding-y-6px">
    <div class="comment-form-content --kuhu-margin-right-10px --kuhu-width-100">
        <div class="comment-form-text-box">
            <textarea name="comment-form-text" cols="" rows="" placeholder="Write a comment..." class="comment-form-text --kuhu-height-36px --kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-dark --kuhu-border-round-25px --kuhu-resize-none --kuhu-font-13px --kuhu-bg-app-light"></textarea>
            <div class="comment-form-text-error-msg --kuhu-padding-x-12px --kuhu-font-12px --kuhu-font-600"></div>
        </div>
        <div class="comment-form-content-error-msg --kuhu-padding-x-12px --kuhu-font-12px --kuhu-font-600"></div>
    </div>

    <div class="comment-form-btn-box --kuhu-text-align-center --kuhu-margin-left-auto">
        <input type="submit" value="Add" class="comment-form-btn --kuhu-padding-y-5px --kuhu-padding-x-11px --kuhu-border-transparent --kuhu-border-round-25px --kuhu-bg-theme --kuhu-color-white --kuhu-font-700">
    </div>
</form>