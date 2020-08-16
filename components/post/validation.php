<?php

function validateTitle() {
    return "";
}

function validateDescriptionBlock($descriptionBlock) {
    return "";
}

function validateDescription($descriptionBlocks) {
    return "";
}

// validate post content all together
function validateContent($title, $descriptionBlocks) {
    // check for errors
    if($title === "" && count($descriptionBlocks) === 0) {
        // if both $title and $description are empty string
        // then nothing is posted actually
        // there should be at least one of them present
        return "both title and description cannot be empty";
    }
    return "";
}

// validate universes all together
function validateUniverses($universes) {
    // this function definition will be written later
    return "";
}

// for validation of single universe, see components/universe/validation.php
?>