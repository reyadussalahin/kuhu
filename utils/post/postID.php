<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class PostID {
    private static $file_name = "postID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open postid file to reset post id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in postid file to reset post id";
            }
            if((fclose($file)) === false) {
                return "can not close postid file after reseting post id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create postid file to store post latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in postid file to store post latest id";
            }
            if((fclose($file)) === false) {
                return "can not close postid file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read post id";
        }
        if(($postId = fgets($file)) === false) {
            return "can not read post id from postid file";
        }
        if((fclose($file)) === false) {
            return "can not close postid file after reading post id";
        }
        return $postId;
    }
    public static function increment() {
        $postID = self::get();
        $incrementedPostID = KuhuNumberSystem::incrementNumber($postID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented post id";
            exit(1);
        }
        if((fputs($file, $incrementedPostID)) === false) {
            echo "can not write incremented post id in postid file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close postid file after writing incremented post id";
            exit(1);
        }
        return true;
    }
}

?>