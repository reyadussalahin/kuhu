<?php

function filterTitle($title) {
    return trim($title);
}

function filterDescription($description) {
    // return trim($description);
    // we're trying to show descripton content
    // as user has written
    // with exact spaces and tabs etc
    // so, we're not trimming it
    // we'll decide later about filtering description later
    return $description;
}

function filterDescriptionBlock($block) {
    // return trim($block);
    // we're trying to show descripton block content
    // as user has written
    // with exact spaces and tabs etc
    // so, we're not trimming it
    // we'll decide later about filtering description block later
    return $block;
}

// filter all universes string
function filterUniverses($universes) {
    // trimming any trailing whitespaces or commas
    // or semicolons
    return trim($universes, " \t\n\r\0\x0B,;");
}

// for validation of single universe, see components/universe/validation.php

?>