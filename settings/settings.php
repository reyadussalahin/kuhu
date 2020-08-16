<?php

class Settings {
    private static $projectRoot = "G:/REYAD/CODES/github/reyadussalahin/kuhu";
    private static $documentRoot = "G:/REYAD/CODES/github/reyadussalahin/kuhu/public";
    private static $domain = "http://localhost";
    // private static $domain = "http://192.168.43.238"; // hotspot by reyad
    // private static $domain = "http://192.168.43.47"; // hotspot by reyan
    private static $port = "8000";

    public static function getProjectRoot() {
        return self::$projectRoot;
    }

    public static function getDocumentRoot() {
        return self::$documentRoot;
    }

    public static function getLiveRoot() {
        return self::$domain . ":" . self::$port;
    }
}

?>