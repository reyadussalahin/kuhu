<?php
// utility that provides option to show favorite universes
require_once("components/user/authentication.php");
?>

<?php if(isAuthenticated()): ?>

<div id="top-favorite-universe-utility" class="--kuhu-box">
    <div id="top-favorite-universe-wrapper" class="--kuhu-box">
        <div id="top-favorite-universe-prefix" class="--kuhu-box --kuhu-padding-top-4px --kuhu-padding-bottom-2px">
            <div class="--kuhu-visual --kuhu-font-15px --kuhu-font-600 --kuhu-color-theme">
                Favorites
            </div>
        </div>

        <div id="top-favorite-universe-container" class="--kuhu-box --kuhu-margin-top-5px">
            <div class="--kuhu-visual --kuhu-font-13px --kuhu-font-500 --kuhu-color-prefix">
                <div class="top-favorite-universe-item-box --kuhu-box --kuhu-padding-y-8px">
                    <div class="top-favorite-universe-item --kuhu-item --kuhu-text">
                        Movies
                    </div>
                </div>
                <div class="top-favorite-universe-item-box --kuhu-box --kuhu-padding-y-8px">
                    <div class="top-favorite-universe-item --kuhu-item --kuhu-text">
                        Football
                    </div>
                </div>
                <div class="top-favorite-universe-item-box --kuhu-box --kuhu-padding-y-8px">
                    <div class="top-favorite-universe-item --kuhu-item --kuhu-text">
                        Politics
                    </div>
                </div>
                <div class="top-favorite-universe-item-box --kuhu-box --kuhu-padding-y-8px">
                    <div class="top-favorite-universe-item --kuhu-item --kuhu-text">
                        Math
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>