<?php

class Time {
    private static $sixHour = 6 * 60 * 60; // its the number of seconds in six hour
    public static function bangladeshDateTime() {
        // bangladeshDateTime returns datetime in mysql format
        return date("Y-m-d H:i:s", $_SERVER["REQUEST_TIME"] + self::$sixHour);
    }
}

?>