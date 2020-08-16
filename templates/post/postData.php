<?php
// this contains all post related data
// like:
// (1) who posted
// (2) post contents
// (3) post share
// (4) date
// (5) no of upvotes and downvotes
// (6) no of comments
?>

<div class="post-data --kuhu-padding-top-20px --kuhu-padding-bottom-4px --kuhu-padding-x-12px">
    <div class="post-header --kuhu-display-flex --kuhu-align-items-center --kuhu-margin-bottom-20px --kuhu-font-13px">
        <div class="post-header-item post-by-prefix --kuhu-padding-right-3px --kuhu-padding-y-2px  --kuhu-font-700">
            by
        </div>
        <div class="post-header-item post-by --kuhu-padding-right-3px --kuhu-padding-y-2px  --kuhu-font-700">
            <a href="" class="--kuhu-color-app-general --kuhu-hover-color-theme --kuhu-hover-text-decoration-underline">
                xxx
            </a>
        </div>
        <div class="post-header-item post-by-suffix --kuhu-padding-right-3px --kuhu-padding-y-2px  --kuhu-font-700">
            .
        </div>
        <div class="post-header-item post-datetime --kuhu-padding-y-2px">
            7 June, 2020
        </div>
        <?php
            // note: see that class "post-share" has been written as
            // "--kuhu-post-share", cause "Adgaurd Adblocker" an adblocker
            // extension creates problem with "post-share" class. "Adgaurd
            // Adblocker" thinks of any html element with css class "post-share"
            // as an add and sets it "display" property to none i.e.
            // "display: none"
            // Hope you get the reason
            // And that's why also changed the "post-share-count" to
            // "--kuhu-post-share-count"
            // hope it all clears now
            // div class="post-header-item post-share">Share</div>
            // <div class="post-header-item post-share-count">(72)</div>
        ?>
        <!-- <div class="post-header-item --kuhu-post-share --kuhu-margin-left-auto --kuhu-font-600 --kuhu-color-theme">Share</div> -->
        <!-- <div class="post-header-item --kuhu-post-share-count">(72)</div> -->
        <div class="post-header-item --kuhu-post-share-box --kuhu-display-flex --kuhu-margin-left-auto --kuhu-color-app-post-btn">
            <div class="--kuhu-post-share-count-box --kuhu-display-flex --kuhu-padding-right-5px --kuhu-padding-y-2px --kuhu-hover-text-decoration-underline">
                <div class="--kuhu-post-share-count --kuhu-padding-right-3px">
                    72
                </div>
                <div class="--kuhu-post-share-suffix">
                    shares
                </div>
            </div>
            <div class="--kuhu-post-share --kuhu-font-600">
                <div class="--kuhu-post-share-cmd-txt --kuhu-padding-y-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-hover-cursor-pointer --kuhu-border-round-2px --kuhu-padding-x-2px">
                    Share
                </div>
            </div>
        </div>
    </div>

    <div class="post-body">
        <div class="post-content">
            <div class="post-title-box --kuhu-font-15px --kuhu-font-700">
                <div class="post-title">
                    <p>This is the title</p>
                </div>
            </div>
            <div class="post-description-box --kuhu-margin-top-8px --kuhu-font-15px">
                <div class="post-description">
                    <!--
                    
                    <div id="post-description-block-template" class="post-description-block --kuhu-margin-bottom-4px">
                        Okay! This is post description. And of course, I can write much more many things, as I want! I can write shakespear, tagore, yeats whoever I want! So, let's begin. can write shakespear, tagore, yeats whoever I want! So, let's begin. uch more many things, as I want! I can write shakespear, tagore, yeats whoever, write much more many things, as I want! I can write shakespear, tagore, yeats whoever I want! So, let's begin. can write shakespear, tagore, yeats whoever I want! So, let's begin. uch more many things, as I want! I can write shakespear, tagore, yeats who n write shakespear, tagore, yeats whoever I want! So, let's begin. can write shakespear, tagore, yeats whoever I want! So, let's begin. uch more many things, as I want! I can write shakespear, tagore, yeats whoever, write much more many things, as I want! And of course, I can write much more many things, as I want! I can write shakespear, tagore, yeats whoever I want! So, let's begin. can write shakespear, tagore, yeats whoever I want! So, let's begin. uch more many things, as I want! I can write shakespear, tagore, yeats whoever, write much more many things, as I want! I can write shakespear, tagore, yeats whoever I want! So, let's begin. can write shakespear, tagore, yeats whoever I want! So, let's begin. uch more many things, as I want! I can write shakespear, tagore, yeats who n write shakespear, tagore, yeats whoever I want!
                    </div>
                    
                    -->
                </div>
            </div>
        </div>

        <div class="post-universes-box --kuhu-display-flex --kuhu-margin-top-10px --kuhu-border-top-1px --kuhu-border-top-light --kuhu-font-13px">
            <div class="post-universes-prefix --kuhu-padding-right-6px">Shared in:</div>
            <div class="post-universes --kuhu-display-flex">
                <!-- <li class="--kuhu-padding-right-4px --kuhu-content-after-comma">welcome</li> -->
                <!-- <li class="--kuhu-padding-right-4px --kuhu-content-after-comma">first-post</li> -->
                <!-- <li class="--kuhu-padding-right-4px">newbie</li> -->
            </div>
        </div>
    </div>

    <div class="post-footer --kuhu-display-flex --kuhu-margin-top-12px --kuhu-font-13px">
        <div class="post-vote --kuhu-display-flex --kuhu-justify-content-space-between --kuhu-align-items-center --kuhu-width-90px --kuhu-line-height-0 --kuhu-padding-top-3px">
            <div class="post-upvote --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-cursor-pointer --kuhu-hover-bg-app-post-btn-bg">
                <div class="post-vote-icon post-upvote-icon">
                    <svg class="post-vote-icon-svg --kuhu-width-20px --kuhu-height-22px" width="24px" height="24px" viewBox="0 0 24 24">
                        <g class="post-vote-icon-svg-stroke post-vote-icon-svg-fill --kuhu-stroke-1-2 --kuhu-stroke-theme" stroke-width="1.5" stroke="#666" fill="none" fill-rule="evenodd" stroke-linejoin="round">
                            <polygon points="12,4 3,12 9,12 9,20 15,20 15,12 21,12"></polygon>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="post-vote-count --kuhu-padding-y-2px --kuhu-color-app-post-btn --kuhu-hover-text-decoration-underline --kuhu-hover-cursor-pointer"> 9 </div>
            
            <div class="post-downvote --kuhu-padding-y-2px --kuhu-border-round-2px --kuhu-hover-cursor-pointer --kuhu-hover-bg-app-post-btn-bg">
                <div class="post-vote-icon post-downvote-icon">
                    <svg class="post-vote-icon-svg --kuhu-width-20px --kuhu-height-22px" width="24px" height="24px" viewBox="0 0 24 24">
                        <g class="post-vote-icon-svg-stroke post-vote-icon-svg-fill --kuhu-stroke-1-2 --kuhu-stroke-theme" stroke="#666" fill="none" stroke-width="1.5" fill-rule="evenodd" stroke-linejoin="round">
                            <polygon transform="translate(12.000000, 12.000000) rotate(-180.000000) translate(-12.000000, -12.000000)" points="12,4 3,12 9,12 9,20 15,20 15,12 21,12"></polygon>
                        </g>
                    </svg>
                </div>
            </div>
        </div>

        <div class="post-comment-box --kuhu-display-flex --kuhu-margin-left-auto --kuhu-color-app-post-btn">
            <div class="post-comment-count-box --kuhu-display-flex --kuhu-padding-right-5px --kuhu-padding-bottom-2px --kuhu-padding-top-5px --kuhu-font-400 --kuhu-hover-text-decoration-underline --kuhu-hover-cursor-pointer">
                <div class="post-comment-count --kuhu-padding-right-3px">
                    32
                </div>
                <div class="post-comment-count-suffix">
                    comments
                </div>
            </div>
            <div class="post-comment --kuhu-padding-bottom-0 --kuhu-padding-top-3px --kuhu-font-600">
                <div class="post-comment-cmd-txt --kuhu-padding-y-2px --kuhu-hover-bg-app-post-btn-bg --kuhu-hover-cursor-pointer --kuhu-border-round-2px --kuhu-padding-x-2px">
                    Comment
                </div>
            </div>
        </div>

    </div>

</div>