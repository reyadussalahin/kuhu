<?php

require_once("settings/settings.php");
require_once("components/user/authentication.php");

$liveRoot = Settings::getLiveRoot();

?>

<!--

<div class="app-navbar navbar">
    <ul class="navbar-items --kuhu-display-flex --kuhu-list-style-none --kuhu-font-15px --kuhu-font-700 --kuhu-padding-x-12px --kuhu-padding-y-10px">
        <li class="nav-item">
            <a class="--kuhu-color-theme" href="<?= $liveRoot; ?>/index.php">Home</a>
        </li>
        <?php if(isAuthenticated()): ?>
            <li class="nav-item --kuhu-padding-x-10px --kuhu-margin-left-auto">
                <a class="--kuhu-color-theme" href="<?= $liveRoot; ?>/u/profile.php">Profile</a>
            </li>
            <li class="nav-item --kuhu-padding-left-10px">
                <a class="--kuhu-color-theme" href="<?= $liveRoot; ?>/u/logout.php">Logout</a>
            </li>
        <?php else: ?>
            <li class="nav-item --kuhu-padding-x-10px --kuhu-margin-left-auto">
                <a class="--kuhu-color-theme" href="<?= $liveRoot; ?>/u/login.php">Login</a>
            </li>
            <li class="nav-item --kuhu-padding-left-10px">
                <a class="--kuhu-color-theme" href="<?= $liveRoot; ?>/u/signup.php">Signup</a>
            </li>
        <?php endif; ?>
    </ul>
</div>

-->

<div class="navbar app-navbar --kuhu-box">
    <div class="nav-item-container --kuhu-box --kuhu-padding-x-12px --kuhu-padding-y-10px">
        <div class="--kuhu-visual --kuhu-font-15px --kuhu-font-700">
            <div class="--kuhu-display-flex">
                <div class="nav-item --kuhu-box">
                    <a id="live-root" class="--kuhu-visual --kuhu-color-theme --kuhu-hover-color-theme-darker" href="<?= $liveRoot; ?>">Home</a>
                </div>
                <?php if(isAuthenticated()): ?>
                    <div class="nav-item --kuhu-box --kuhu-padding-x-10px --kuhu-margin-left-auto">
                        <a class="--kuhu-visual --kuhu-color-theme --kuhu-hover-color-theme-darker" href="<?= $liveRoot; ?>/user/profile.php">Profile</a>
                    </div>
                    <div class="nav-item --kuhu-box --kuhu-padding-left-10px">
                        <a class="--kuhu-visual --kuhu-color-theme --kuhu-hover-color-theme-darker" href="<?= $liveRoot; ?>/user/logout.php">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="nav-item --kuhu-box --kuhu-padding-x-10px --kuhu-margin-left-auto">
                        <a class="--kuhu-visual --kuhu-color-theme --kuhu-hover-color-theme-darker" href="<?= $liveRoot; ?>/user/login.php">Login</a>
                    </div>
                    <div class="nav-item --kuhu-box --kuhu-padding-left-10px">
                        <a class="--kuhu-visual --kuhu-color-theme --kuhu-hover-color-theme-darker" href="<?= $liveRoot; ?>/user/signup.php">Signup</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>