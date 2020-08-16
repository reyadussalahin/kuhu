<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class ReplyTextID {
    private static $file_name = "replyTextID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open replyTextID file to reset replyText id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in replyTextID file to reset replyText id";
            }
            if((fclose($file)) === false) {
                return "can not close replyTextID file after reseting replyText id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create replyTextID file to store replyText latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in replyTextID file to store replyText latest id";
            }
            if((fclose($file)) === false) {
                return "can not close replyTextID file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read replyText id";
        }
        if(($replyTextId = fgets($file)) === false) {
            return "can not read replyText id from replyTextID file";
        }
        if((fclose($file)) === false) {
            return "can not close replyTextid file after reading replyText id";
        }
        return $replyTextId;
    }
    public static function increment() {
        $replyTextID = self::get();
        $incrementedReplyTextID = KuhuNumberSystem::incrementNumber($replyTextID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented replyText id";
            exit(1);
        }
        if((fputs($file, $incrementedReplyTextID)) === false) {
            echo "can not write incremented replyText id in replyTextID file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close replyTextID file after writing incremented replyText id";
            exit(1);
        }
        return true;
    }
}

?>