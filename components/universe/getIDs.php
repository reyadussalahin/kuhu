<?php
// this file takes an array of universeNames as input
// and returns an array of universeIDs as output

require_once("components/universe/getID.php");


function getUniverseIDs($universeNames) {
    $universeIDs = [];

    foreach($universeNames as $name) {
        $id = getUniverseID($name);
        // check if $id is actually an error
        // $id would be an error if it contains space
        if(strpos($id, " ") !== false) {
            return $id; // cause it is an error
        }
        $universeIDs[] = $id;
    }

    return $universeIDs;
}


?>