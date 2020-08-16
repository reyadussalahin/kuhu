<?php
// this templates provides the view(i.e. html) for creating post
// it's basically a html form where the user write/uploads content
// of his/her post

// post has three main section
// topic/title
// context/description
// tags/universes

require_once("settings/settings.php");

$liveRoot = Settings::getLiveRoot();

// note: the form action is "https://kuhu.com/x/post/add.php" which handles ajax request
//       to add the post to the database properly
// the post is created when #post-create-btn is clicked(see the input with id post-create-btn)
?>

<form id="post-form" action="<?= $liveRoot; ?>/x/post/add.php" method="POST" class="post-form --kuhu-box">
    <div id="post-form-content" class="--kuhu-box">
        <div id="post-form-title-box" class="--kuhu-visual --kuhu-font-15px --kuhu-font-700">
            <textarea name="post-form-title" id="post-form-title" cols="" rows="" placeholder="The title" class="--kuhu-box --kuhu-width-100 --kuhu-height-40px --kuhu-padding-y-8px --kuhu-padding-x-6px --kuhu-padding-x-md-9px --kuhu-padding-x-lg-12px --kuhu-border-bottom-solid --kuhu-border-bottom-1px --kuhu-border-bottom-light"></textarea>
        </div>
        
        <div id="post-form-description-box" class="--kuhu-visual --kuhu-font-15px --kuhu-font-500">
            <textarea name="post-form-description" id="post-form-description" cols="" rows="" placeholder="Here goes the post description..." class="--kuhu-box --kuhu-width-100 --kuhu-height-100px --kuhu-padding-y-8px --kuhu-padding-x-6px --kuhu-padding-x-md-9px --kuhu-padding-x-lg-12px --kuhu-border-bottom-solid --kuhu-border-bottom-1px --kuhu-border-bottom-light"></textarea>
        </div>

        <div id="post-form-content-error-msg-box" class="--kuhu-box --kuhu-padding-left-12px --kuhu-font-12px">
            <div id="post-form-content-error-msg" class="--kuhu-font-600 --kuhu-font-arial">
            </div>
        </div>
    </div>


    <div id="post-form-universes-box" class="--kuhu-visual --kuhu-font-13px --kuhu-font-500">
        <textarea name="post-form-universes" id="post-form-universes" cols="" rows="" placeholder="universes where you would like to share your post like 'movie', 'politics', 'math' etc..." class="--kuhu-box --kuhu-width-100 --kuhu-height-40px --kuhu-padding-y-8px --kuhu-padding-x-6px --kuhu-padding-x-md-9px --kuhu-padding-x-lg-12px --kuhu-border-bottom-solid --kuhu-border-bottom-1px --kuhu-border-bottom-light"></textarea>
        
        <div id="post-form-universes-error-msg-box" class="--kuhu-padding-left-12px --kuhu-font-12px --kuhu-font-600 --kuhu-font-arial">
            <div id="post-form-universes-error-msg" class="--kuhu-visual --kuhu-font-600 --kuhu-font-arial">
            </div>
        </div>
        <div id="post-form-universes-rule" class="--kuhu-padding-left-12px --kuhu-font-arial --kuhu-font-12px --kuhu-color-rule">
            ** seperate each universe name with a ,(comma)[only characters, digits and - are allowed]
        </div>
    </div>
    

    <div id="post-form-btn-box" class="--kuhu-padding-top-16px --kuhu-text-right">
        <input id="post-form-btn" type="submit" value="Create" class="post-from-btn --kuhu-box --kuhu-padding-y-4px --kuhu-padding-x-4px --kuhu-border-round-3px --kuhu-bg-theme --kuhu-color-white --kuhu-font-bold --kuhu-font-13px --kuhu-hover-bg-theme-darker">
    </div>

</form>