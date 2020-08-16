<?php
// (1) who comments
// (2) time of comment
// (3) comment data
// (4) comment upvotes and downvotes
// (5) comment reply counts
?>

<div class="comment-data --kuhu-padding-y-6px --kuhu-font-13px">
    <div class="comment-header-body-wrapper --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-round-10px --kuhu-bg-app-light">
        <div class="comment-header --kuhu-display-flex --kuhu-margin-bottom-5px">
            <div class="comment-by --kuhu-padding-right-3px --kuhu-font-600">
                <a href="" class="--kuhu-color-prefix --kuhu-hover-color-theme --kuhu-hover-text-decoration-underline">
                    xxx
                </a>
            </div>
            <div class="comment-by-suffix --kuhu-padding-right-3px --kuhu-font-600">.</div>
            <div class="comment-datetime">7th June, 2020</div>
        </div>
        <div class="comment-body --kuhu-font-13px">
            <div class="comment-content">
                <div class="comment-text">
                    Okay, this is a comment!
                </div>
            </div>
        </div>
    </div>
    <div class="comment-footer --kuhu-margin-left-10px --kuhu-display-flex --kuhu-align-items-center --kuhu-font-13px">
        <div class="comment-vote --kuhu-width-90px --kuhu-padding-top-1px --kuhu-display-flex --kuhu-justify-content-space-between --kuhu-align-items-center --kuhu-line-height-0">
            <div class="comment-upvote --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-hover-cursor-pointer">
                <div class="comment-vote-icon comment-upvote-icon">
                    <svg class="comment-vote-icon-svg --kuhu-width-18px --kuhu-height-20px" width="24px" height="24px" viewBox="0 0 24 24">
                        <g class="comment-vote-icon-svg-stroke comment-vote-icon-svg-fill --kuhu-stroke-1-2" stroke-width="1.5" stroke="#666" fill="none" fill-rule="evenodd" stroke-linejoin="round">
                            <polygon points="12,4 3,12 9,12 9,20 15,20 15,12 21,12"></polygon>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="comment-vote-count --kuhu-padding-y-2px --kuhu-color-app-post-btn --kuhu-hover-text-decoration-underline --kuhu-hover-cursor-pointer"> 3 </div>
            
            <div class="comment-downvote --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-hover-cursor-pointer">
                <div class="comment-vote-icon comment-downvote-icon">
                    <svg class="comment-vote-icon-svg --kuhu-width-18px --kuhu-height-20px" width="24px" height="24px" viewBox="0 0 24 24">
                        <g class="comment-vote-icon-svg-stroke comment-vote-icon-svg-fill --kuhu-stroke-1-2" stroke="#666" fill="none" stroke-width="1.5" fill-rule="evenodd" stroke-linejoin="round">
                            <polygon transform="translate(12.000000, 12.000000) rotate(-180.000000) translate(-12.000000, -12.000000)" points="12,4 3,12 9,12 9,20 15,20 15,12 21,12"></polygon>
                        </g>
                    </svg>
                </div>
            </div>
        </div>

        <div class="comment-reply --kuhu-margin-left-28px --kuhu-display-flex --kuhu-align-items-flex-start">
            <div class="comment-reply-prefix --kuhu-font-500 --kuhu-color-app-post-btn  --kuhu-padding-x-4px --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-hover-cursor-pointer">Reply</div>
            <!-- <div class="comment-reply-count">(32)</div> -->
        </div>

    </div>
</div>