<?php
// this file is for testing universeID utility

// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "universeID.php"); // for testing purposes
require_once("utils/universe/universeID.php");

// UniverseID::reset();

for($i=0; $i<100; $i++) {
    $universeID = UniverseID::get();
    echo "post-text-id: " . $universeID . "\n";
    universeID::increment();
}

?>