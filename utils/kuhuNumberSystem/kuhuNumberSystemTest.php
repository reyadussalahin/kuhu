<?php
require_once("utils/kuhuNumberSytem/kuhuNumberSystem.php");
// require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "kuhuNumberSystem.php");

function testKuhuNumberSystem() {
    $n = "0";
    for($i=0; $i<100; $i++) {
        echo $i . ": " . $n . "\n";
        $n = KuhuNumberSystem::incrementNumber($n);
    }
    echo "done.\n";
}

testKuhuNumberSystem();

?>