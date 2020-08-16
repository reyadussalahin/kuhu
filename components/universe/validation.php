<?php
// validate a single universe
function validateUniverse($universe) {
    $len = strlen($universe);
    for($i=0; $i<$len; $i++) {
        $ch = $universe[$i];
        if(!ctype_alnum($ch) && !($ch === "-")) {
            return "please, enter the universe names in correct format";
        }
    }
    // if($universe is some vulgar word) {
    //     return "can't use $universe as a universe name. please use some nice word.";
    // }
    return "";
}

?>

