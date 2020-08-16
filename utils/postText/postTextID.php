<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class PostTextID {
    private static $file_name = "postTextID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open postTextID file to reset postText id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in postTextID file to reset postText id";
            }
            if((fclose($file)) === false) {
                return "can not close postTextID file after reseting postText id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create postTextID file to store postText latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in postTextID file to store postText latest id";
            }
            if((fclose($file)) === false) {
                return "can not close postTextID file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read postText id";
        }
        if(($postTextId = fgets($file)) === false) {
            return "can not read postText id from postTextID file";
        }
        if((fclose($file)) === false) {
            return "can not close postTextid file after reading postText id";
        }
        return $postTextId;
    }
    public static function increment() {
        $postTextID = self::get();
        $incrementedPostTextID = KuhuNumberSystem::incrementNumber($postTextID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented postText id";
            exit(1);
        }
        if((fputs($file, $incrementedPostTextID)) === false) {
            echo "can not write incremented postText id in postTextID file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close postTextID file after writing incremented postText id";
            exit(1);
        }
        return true;
    }
}

?>