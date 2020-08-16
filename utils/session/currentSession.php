<?php


function startCurrentSession() {
    if(!isset($_SESSION)) {
        session_set_cookie_params(Session::getLifetime());
        session_start();
    }
}



function getCurrentSessionID() {
    if(!isset($_SESSION)) {
        session_set_cookie_params(Session::getLifetime());
        session_start();
    }
    return session_id();
}


?>