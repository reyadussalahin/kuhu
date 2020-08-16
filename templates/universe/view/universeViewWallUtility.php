<?php
// this file provides utility for universe view page's wall utility
?>

<?php

// this file provides template for universe-view-wall
// which contains:
// (1) wall(wall picture/cover photo)
// (2) universe-view picture
// (3) bio
?>

<div id="universe-view-wall-utility" class="--kuhu-margin-bottom-12px">
    <div id="universe-view-wall-container" class="--kuhu-width-100 --kuhu-border-x-lg-1px --kuhu-border-bottom-lg-1px --kuhu-border-light --kuhu-border-bottom-round-lg-3px --kuhu-bg-white">
        <div id="universe-view-wall-image-box">
            <div id="universe-view-wall-cover-image-box" class="--kuhu-width-100 --kuhu-height-240px --kuhu-bg-skyblue">
                <div id="universe-view-wall-cover-image"></div>
            </div>
        </div>
        
        <div id="universe-view-wall-public-info-box" class="--kuhu-margin-top-0 --kuhu-width-100 --kuhu-padding-x-12px --kuhu-display-flex">
            <div id="universe-view-wall-public-info" class="--kuhu-padding-top-8px">
                <div id="universe-view-wall-public-name" class="--kuhu-font-20px --kuhu-font-700">
                    <?= $universeName; ?>
                </div>
                <div id="universe-view-wall-public-followers" class="--kuhu-padding-top-5px --kuhu-font-13px --kuhu-font-500 --kuhu-color-theme">
                    21.7M Followers
                </div>
            </div>
            
            <div id="universe-view-wall-public-follow-btn-box-display" class="--kuhu-margin-left-auto --kuhu-width-25 --kuhu-padding-top-10px --kuhu-display-flex --kuhu-justify-content-flex-end">
                <div class="--kuhu-height-max-content --kuhu-padding-top-4px --kuhu-padding-bottom-4px --kuhu-padding-x-16px --kuhu-border-round-25px --kuhu-font-14px --kuhu-font-600 --kuhu-bg-theme --kuhu-color-white">
                    Following
                </div>
            </div>
        </div>

        <div id="universe-view-wall-intro-description-box" class="--kuhu-margin-top-16px --kuhu-padding-x-12px --kuhu-padding-bottom-16px --kuhu-font-15px --kuhu-font-500">
            <div id="universe-view-wall-intro-description">
                This universe discusses all aspect of sport programming. It contains posts, news, questions, problems and answers related to sport programming.
            </div>
        </div>
    </div>
</div>