<?php
// (1) who replys
// (2) time of reply
// (3) reply data
// (4) reply upvotes and downvotes
// (5) reply reply counts
?>

<div class="reply-data --kuhu-padding-y-6px --kuhu-font-13px">
    <div class="reply-header-body-wrapper --kuhu-padding-y-8px --kuhu-padding-x-12px --kuhu-border-round-10px --kuhu-bg-app-light">
        <div class="reply-header --kuhu-display-flex --kuhu-margin-bottom-5px">
            <div class="reply-by --kuhu-padding-right-3px --kuhu-font-600">
                <a href="" class="--kuhu-color-prefix --kuhu-hover-color-theme --kuhu-hover-text-decoration-underline">
                    xxx
                </a>
            </div>
            <div class="reply-by-suffix --kuhu-padding-right-3px --kuhu-font-600">.</div>
            <div class="reply-datetime">7th June, 2020</div>
        </div>
        <div class="reply-body --kuhu-font-13px">
            <div class="reply-content">
                <div class="reply-text">
                    Okay, this is a reply!
                </div>
            </div>
        </div>
    </div>
    <div class="reply-footer --kuhu-margin-left-10px --kuhu-display-flex --kuhu-align-items-center --kuhu-font-13px">
        <div class="reply-vote --kuhu-width-90px --kuhu-padding-top-1px --kuhu-display-flex --kuhu-justify-content-space-between --kuhu-align-items-center --kuhu-line-height-0">
            <div class="reply-upvote --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-hover-cursor-pointer">
                <div class="reply-vote-icon reply-upvote-icon">
                    <svg class="reply-vote-icon-svg --kuhu-width-18px --kuhu-height-20px" width="24px" height="24px" viewBox="0 0 24 24">
                        <g class="reply-vote-icon-svg-stroke reply-vote-icon-svg-fill --kuhu-stroke-1-2" stroke-width="1.5" stroke="#666" fill="none" fill-rule="evenodd" stroke-linejoin="round">
                            <polygon points="12,4 3,12 9,12 9,20 15,20 15,12 21,12"></polygon>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="reply-vote-count --kuhu-padding-y-2px --kuhu-color-app-post-btn --kuhu-hover-text-decoration-underline --kuhu-hover-cursor-pointer"> 3 </div>
            
            <div class="reply-downvote --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-hover-cursor-pointer">
                <div class="reply-vote-icon reply-downvote-icon">
                    <svg class="reply-vote-icon-svg --kuhu-width-18px --kuhu-height-20px" width="24px" height="24px" viewBox="0 0 24 24">
                        <g class="reply-vote-icon-svg-stroke reply-vote-icon-svg-fill --kuhu-stroke-1-2" stroke="#666" fill="none" stroke-width="1.5" fill-rule="evenodd" stroke-linejoin="round">
                            <polygon transform="translate(12.000000, 12.000000) rotate(-180.000000) translate(-12.000000, -12.000000)" points="12,4 3,12 9,12 9,20 15,20 15,12 21,12"></polygon>
                        </g>
                    </svg>
                </div>
            </div>
        </div>

        <div class="reply-reply --kuhu-margin-left-28px --kuhu-display-flex --kuhu-align-items-flex-start">
            <div class="reply-reply-prefix --kuhu-font-500 --kuhu-color-app-post-btn --kuhu-font-600 --kuhu-color-app-post-btn  --kuhu-padding-x-4px --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-border-round-2px --kuhu-hover-cursor-pointer">Reply</div>
            <!-- <div class="reply-reply-count">(32)</div> -->
        </div>
    </div>
</div>