<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class UserID {
    private static $file_name = "userID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open userid file to reset user id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in userid file to reset user id";
            }
            if((fclose($file)) === false) {
                return "can not close userid file after reseting user id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create userid file to store user latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in userid file to store user latest id";
            }
            if((fclose($file)) === false) {
                return "can not close userid file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read user id";
        }
        if(($userId = fgets($file)) === false) {
            return "can not read user id from userid file";
        }
        if((fclose($file)) === false) {
            return "can not close userid file after reading user id";
        }
        return $userId;
    }
    public static function increment() {
        $userID = self::get();
        $incrementedUserID = KuhuNumberSystem::incrementNumber($userID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented user id";
            exit(1);
        }
        if((fputs($file, $incrementedUserID)) === false) {
            echo "can not write incremented user id in userid file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close userid file after writing incremented user id";
            exit(1);
        }
        return true;
    }
}

?>