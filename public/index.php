<?php
// some basic info about this project
//
// note(include_path):
//       "G:/REYAD/CODES/github/reyadussalahin/kuhu" is added to include_path
//       that's why you would find the include relative to the directory
// note(doc_root):
//       "G:/REYAD/CODES/github/reyadussalahin/kuhu/public" is set as doc_root in php.ini
//       no need to provide it explicitly in the command line

require_once("settings/settings.php");
require_once("components/user/getInfo.php");

$liveRoot = Settings::getLiveRoot();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Kuhu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link type="text/css" rel="stylesheet" href="<?= $liveRoot;?>/assets/css/style.css"> -->
    <link type="text/css" rel="stylesheet" href="<?= $liveRoot; ?>/assets/css/kuhu.css">
</head>

<body>
    <div class="app-global-container --kuhu-min-height-viewport --kuhu-bg-app-global">
        <div class="--kuhu-top-bar-index-page --kuhu-box --kuhu-position-sticky --kuhu-top-0 --kuhu-z-index-300 --kuhu-margin-bottom-lg-12px">
            <!-- index page top bar has a margin of 12px in bottom, that's why we need to paste topbar utility inside a div -->
            <!-- if you observe page like user/profile.php or universe/view.php, you'll see that topbar utility is pasted directly -->
            <?php
                include("templates/general/topbar/topbarUtility.php");
            ?>
        </div>

        <div class="--kuhu-container --kuhu-margin-x-auto --kuhu-padding-x-lg-12px">
            <div class="--kuhu-row">
                <div class="--kuhu-col-lg-16vw --kuhu-display-none --kuhu-display-lg-block">
                    <?php
                        include("templates/universe/topFavorite/topFavoriteUtility.php");
                    ?>
                </div>
                
                <div class="--kuhu-col-100vw --kuhu-col-md-65vw --kuhu-col-lg-50vw --kuhu-margin-left-lg-12px">
                    <!-- this left margin of 12px is just a margin gap between favorite utility and postUtility -->
                    <?php
                        include("templates/post/postUtility.php");
                    ?>
                </div>

                <div class="--kuhu-col-md-30vw --kuhu-col-lg-24vw --kuhu-display-none --kuhu-display-md-block --kuhu-margin-left-auto">
                    <?php
                        include("templates/universe/topTrending/topTrendingUtility.php");
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="app-cell-templates --kuhu-display-none">
        <?php
            include("templates/general/cellTemplates.php");
        ?>
    </div>
    <script type="text/javascript" src="<?= $liveRoot; ?>/assets/js/index.js"></script>
</body>

</html>