<?php

// this file provides template for profile wall
// which contains:
// (1) wall(wall picture/cover photo)
// (2) profile picture
// (3) bio
?>

<div id="profile-wall-utility" class="--kuhu-margin-bottom-12px">
    <div id="profile-wall-container" class="--kuhu-width-100 --kuhu-border-x-lg-1px --kuhu-border-bottom-lg-1px --kuhu-border-light --kuhu-border-bottom-round-lg-3px --kuhu-bg-white">
        <div id="profile-wall-image-box">
            <div id="profile-wall-cover-image-box" class="--kuhu-width-100 --kuhu-height-240px --kuhu-bg-skyblue">
                <div id="profile-wall-cover-image"></div>
            </div>
            <div id="profile-wall-display-image-box" class="--kuhu-margin-x-auto --kuhu-margin-top-neg-100px --kuhu-width-140px --kuhu-height-140px --kuhu-border-circle --kuhu-bg-profile">
                <div id="profile-wall-display-image"></div>
            </div>
        </div>
        
        <div id="profile-wall-public-info-box" class="--kuhu-margin-top-8px --kuhu-width-100 --kuhu-display-flex --kuhu-justify-content-space-between">
            <div id="profile-wall-dummy-space" class="--kuhu-text-left --kuhu-width-25 ">
                <!-- dummy node just for centering items properly -->
            </div>

            <div id="profile-wall-public-info" class="--kuhu-width-50 --kuhu-padding-top-8px --kuhu-text-center">
                <div id="profile-wall-public-name" class="--kuhu-font-20px --kuhu-font-700">
                    <?= $firstName . " " . $lastName; ?>
                </div>
                <div id="profile-wall-public-followers" class="--kuhu-padding-top-5px --kuhu-font-13px --kuhu-font-500 --kuhu-color-theme">
                    21.7M Followers
                </div>
            </div>
            
            <div id="profile-wall-public-follow-btn-box-display" class="--kuhu-width-25 --kuhu-padding-top-8px --kuhu-display-flex --kuhu-justify-content-flex-end">
                <div class="--kuhu-margin-right-12px --kuhu-margin-top-neg-40px --kuhu-height-max-content --kuhu-padding-top-4px --kuhu-padding-bottom-4px --kuhu-padding-x-16px --kuhu-border-round-25px --kuhu-font-14px --kuhu-font-600 --kuhu-bg-theme --kuhu-color-white">
                    Following
                </div>
            </div>
        </div>

        <div id="profile-wall-intro-description-box" class="--kuhu-margin-left-1-5vw --kuhu-margin-top-16px --kuhu-padding-bottom-16px --kuhu-text-center --kuhu-font-15px --kuhu-font-500">
            <div id="profile-wall-intro-description">
                You would go blind seeing my pure awesomeness.
            </div>
        </div>
    </div>
</div>