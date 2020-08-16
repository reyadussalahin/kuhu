<?php
// this file contains function for validating reply data

function validateReplyText($text) {
    if($text === "") {
        return "an empty string can not be accepted as a reply";
    }
    return "";
}

?>