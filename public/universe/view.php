<?php
// this script serves the view of a universe.
// an universe name is expexted through get reuqest
// url format: https://kuhu.com/universe/view.php?universe=name_of_universe

require_once("settings/settings.php");
require_once("components/user/authentication.php");

if($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo "invalid request";
    exit(1);
}

if(!isset($_GET["universe"])) {
    echo "Universname is not provided with the request. Please request with a universe name.";
    exit(1);
}

$universeName = $_GET["universe"]; // retrieve universe name

$universeName = str_replace("-", " ", $universeName);

$pieces = explode(" ", $universeName);
foreach($pieces as &$piece) {
    $piece[0] = strtoupper($piece[0]);
}

$universeName = implode(" ", $pieces);


$liveRoot = Settings::getLiveRoot();
?>

<!DOCTYPE html>
<html>
<head>
    <title>
        <?= $universeName; ?> - Kuhu
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="<?= $liveRoot;?>/assets/css/kuhu.css">
</head>

<body>
    <div class="app-global-container --kuhu-bg-app-global --kuhu-min-height-viewport">
        <?php
            include("templates/general/topbar/topbarUtility.php");
        ?>

        <div class="--kuhu-container  --kuhu-margin-x-auto --kuhu-padding-x-lg-12px">
            <div class="--kuhu-row">
                <div class="--kuhu-col-md-65 --kuhu-col-lg-72vw --kuhu-min-height-viewport">
                    <?php
                        include("templates/universe/view/universeViewWallUtility.php");
                    ?>
                    <div class="--kuhu-container">
                        <div class="--kuhu-row">
                            <div class="--kuhu-col-lg-20vw">
                                <!-- intro utility -->
                                <?php
                                    include("templates/universe/topContributor/topContributorUtility.php");
                                ?>
                            </div>
                            <div class="--kuhu-col-lg-50vw --kuhu-margin-left-auto">
                                <!-- profile Bar Utility -->
                                <?php
                                    include("templates/universe/view/universeViewBarUtility.php");
                                ?>
                                <!-- profile services -->
                                <?php
                                    include("templates/post/postUtility.php");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="--kuhu-display-none --kuhu-display-md-block --kuhu-col-md-32 --kuhu-col-lg-24vw --kuhu-margin-left-auto --kuhu-margin-top-12px">
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