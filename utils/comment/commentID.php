<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class CommentID {
    private static $file_name = "commentID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open comment id file to reset comment id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in comment id file to reset comment id";
            }
            if((fclose($file)) === false) {
                return "can not close comment id file after reseting comment id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create comment id file to store comment latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in comment id file to store comment latest id";
            }
            if((fclose($file)) === false) {
                return "can not close comment id file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read comment id";
        }
        if(($commentId = fgets($file)) === false) {
            return "can not read comment id from comment id file";
        }
        if((fclose($file)) === false) {
            return "can not close commentid file after reading comment id";
        }
        return $commentId;
    }
    public static function increment() {
        $commentID = self::get();
        $incrementedCommentID = KuhuNumberSystem::incrementNumber($commentID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented comment id";
            exit(1);
        }
        if((fputs($file, $incrementedCommentID)) === false) {
            echo "can not write incremented comment id in commentid file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close commentid file after writing incremented comment id";
            exit(1);
        }
        return true;
    }
}

?>