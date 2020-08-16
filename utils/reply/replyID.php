<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class ReplyID {
    private static $file_name = "replyID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open reply id file to reset reply id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in reply id file to reset reply id";
            }
            if((fclose($file)) === false) {
                return "can not close reply id file after reseting reply id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create reply id file to store reply latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in reply id file to store reply latest id";
            }
            if((fclose($file)) === false) {
                return "can not close reply id file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read reply id";
        }
        if(($replyId = fgets($file)) === false) {
            return "can not read reply id from reply id file";
        }
        if((fclose($file)) === false) {
            return "can not close replyid file after reading reply id";
        }
        return $replyId;
    }
    public static function increment() {
        $replyID = self::get();
        $incrementedReplyID = KuhuNumberSystem::incrementNumber($replyID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented reply id";
            exit(1);
        }
        if((fputs($file, $incrementedReplyID)) === false) {
            echo "can not write incremented reply id in replyid file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close replyid file after writing incremented reply id";
            exit(1);
        }
        return true;
    }
}

?>