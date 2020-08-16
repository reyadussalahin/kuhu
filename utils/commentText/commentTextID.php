<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class CommentTextID {
    private static $file_name = "commentTextID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open commentTextID file to reset commentText id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in commentTextID file to reset commentText id";
            }
            if((fclose($file)) === false) {
                return "can not close commentTextID file after reseting commentText id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create commentTextID file to store commentText latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in commentTextID file to store commentText latest id";
            }
            if((fclose($file)) === false) {
                return "can not close commentTextID file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read commentText id";
        }
        if(($commentTextId = fgets($file)) === false) {
            return "can not read commentText id from commentTextID file";
        }
        if((fclose($file)) === false) {
            return "can not close commentTextid file after reading commentText id";
        }
        return $commentTextId;
    }
    public static function increment() {
        $commentTextID = self::get();
        $incrementedCommentTextID = KuhuNumberSystem::incrementNumber($commentTextID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented commentText id";
            exit(1);
        }
        if((fputs($file, $incrementedCommentTextID)) === false) {
            echo "can not write incremented commentText id in commentTextID file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close commentTextID file after writing incremented commentText id";
            exit(1);
        }
        return true;
    }
}

?>