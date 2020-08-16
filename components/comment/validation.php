<?php
// this file contains function for validating comment data

function validateCommentText($text) {
    if($text === "") {
        return "an empty string can not be accepted as a comment";
    }
    return "";
}

?>