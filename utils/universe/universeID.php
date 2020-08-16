<?php

require_once("utils/kuhuNumberSystem/kuhuNumberSystem.php");
// require_once(dirname(dirname(__FILE__)) . "/kuhuNumberSystem/kuhuNumberSystem.php"); // for testing

class UniverseID {
    private static $file_name = "universeID.txt";
    private static function filePath() {
        return dirname(__FILE__). DIRECTORY_SEPARATOR . self::$file_name;
    }
    public static function reset() {
        if(self::get() !== "0") {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not open universeid file to reset universe id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in universeid file to reset universe id";
            }
            if((fclose($file)) === false) {
                return "can not close universeid file after reseting universe id";
            }
        }
        return true;
    }
    public static function get() {
        if(!file_exists(self::filePath())) {
            if(($file = fopen(self::filePath(), "w")) === false) {
                return "can not create universeid file to store universe latest id";
            }
            if((fputs($file, "0")) === false) {
                return "can not write in universeid file to store universe latest id";
            }
            if((fclose($file)) === false) {
                return "can not close universeid file";
            }
        }
        if(($file = fopen(self::filePath(), "r")) === false) {
            return "can not open file to read universe id";
        }
        if(($universeId = fgets($file)) === false) {
            return "can not read universe id from universeid file";
        }
        if((fclose($file)) === false) {
            return "can not close universeid file after reading universe id";
        }
        return $universeId;
    }
    public static function increment() {
        $universeID = self::get();
        $incrementedUniverseID = KuhuNumberSystem::incrementNumber($universeID);
        if(($file = fopen(self::filePath(), "w")) === false) {
            echo "can not open file for writing incremented universe id";
            exit(1);
        }
        if((fputs($file, $incrementedUniverseID)) === false) {
            echo "can not write incremented universe id in universeid file";
            exit(1);
        }
        if((fclose($file)) === false) {
            echo "can not close universeid file after writing incremented universe id";
            exit(1);
        }
        return true;
    }
}

?>