<?php

require_once("components/post/filter.php");

function processTitle($title) {
    return $title;
}

function processDescription($description) {
    // we'll decide it later, how to parse a description
    // into blocks
    // we just can't parse description as 
    // $blocks = explode("\n", $description);
    $blocks = [ $description ];
    $processedBlocks = [];
    foreach($blocks as $block) {
        $processedBlocks[] = filterDescriptionBlock($block);
    }
    return $processedBlocks;
}

function processUniverses($universes) {
    // about Universes processing
    // splitting up Universes
    // note: (1) it is assumed post Universes are seperated by commas
    //       (2) it is also assumed that Universes does not contain spaces
    //       (3) it is assumed that users used '-' in place of spaces
    //       (4) and if Universes contain spaces it'll be replaced by '-' (called 'hiphen', some also say 'dash')
    $universes = str_replace(";", ",", $universes); // replacing ";" with "," cause,
    // it's a common mistake to use ";" instead of ","
    $universes = explode(",", $universes); // exploding universes into universe array
    $universesProcessed = [];
    foreach($universes as $universe) {
        $universe = trim($universe); // trimming $universe
        $universe = str_replace([" ", "\t", "\n", "\n\r"], "-", $universe); // replacing " ", "\t" etc.(whitespaces) with "-"(hiphen/dash)
        // str_replace is also changing "+" characters into "-" characters, i've
        // to look into that in future
        if(strlen($universe) > 0) {
            $universesProcessed[] = $universe;
        }
    }
    return $universesProcessed;
}

?>