<?php
// reply form
// i.e. the form which says "write a reply"
?>

<div class="reply-form">
    <div class="reply-at-box --kuhu-padding-x-12px --kuhu-padding-top-6px --kuhu-font-13px --kuhu-font-600">
            <div class="reply-at-id --kuhu-display-none">
                <!-- i.e. the id of the parent reply of this reply -->
            </div>
            <div class="reply-at-user">
                <!-- no-one by default -->
            </div>
    </div>
    
    <form action="<?= $liveRoot; ?>/x/reply/add.php" method="POST" class="--kuhu-display-flex --kuhu-align-items-center --kuhu-padding-y-6px">
        <div class="reply-form-content --kuhu-margin-right-10px --kuhu-width-100">
            <div class="reply-form-text-box">
                <textarea name="reply-form-text" cols="" rows="" placeholder="Write a reply..." class="reply-form-text --kuhu-height-36px --kuhu-width-100 --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-1px --kuhu-border-dark --kuhu-border-round-25px --kuhu-resize-none --kuhu-font-13px --kuhu-bg-app-light"></textarea>
                <div class="reply-form-text-error-msg --kuhu-padding-x-12px --kuhu-font-12px --kuhu-font-600"></div>
            </div>
            <div class="reply-form-content-error-msg --kuhu-padding-x-12px --kuhu-font-12px --kuhu-font-600"></div>
        </div>

        <div class="reply-form-btn-box --kuhu-text-center --kuhu-margin-left-auto">
            <input type="submit" value="Add" class="reply-form-btn --kuhu-padding-y-5px --kuhu-padding-x-11px  --kuhu-border-transparent --kuhu-border-round-25px --kuhu-bg-theme --kuhu-color-white --kuhu-font-700">
        </div>
    </form>
</div>