<?php
// this function takes universeIDs as input, and
// returns universeNames as output

require_once("components/universe/getName.php");

function getUniverseNames($universeIDs) {

    $universeNames = [];

    foreach($universeIDs as $universeID) {
        $universeName = getUniverseName($universeID);

        // note: $universeName does not contain any spaces while
        //       stored in database
        //       so, check if it is an error or not
        if(strpos($universeName, " ") !== false) {
            return $universeName; // cause $universeName is actually an error
        }

        $universeNames[] = $universeName;
    }

    return $universeNames;
}

?>