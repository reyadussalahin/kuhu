<?php

class Database {
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $name = "kuhu";
    
    // public static function host() {
    //     return self::$host;
    // }
    // public static function user() {
    //     return self::$user;
    // }
    // public static function password() {
    //     return self::$pass;
    // }
    // public static function name() {
    //     return self::$name;
    // }

    public static function getConnection() {
        $conn = new mysqli(self::$host, self::$user, self::$pass, self::$name);
        if($conn->connect_errno) {
            echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
            return null;
        }
        return $conn;
    }

    public static function create() {
        /**
         * setting up connection with mysql server
         */
        $conn = new mysqli(self::$host, self::$user, self::$pass);
        if($conn->connect_errno) {
            echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
            return false;
        }

        /***
        *
        * Creating database "kuhu" 
        *
        ***/
        if($stmt = $conn->prepare("CREATE DATABASE IF NOT EXISTS " . self::$name)) {
            if(!$stmt->execute()) {
                echo "Couldn't create database " . self::$name . "\n";
                $stmt->close();
                $conn->close();
                return false;
            }
            $stmt->close();
        } else {
            echo "couldn't create statement for creating database\n";
            $conn->close();
            return false;
        }

        /**
         * selecting Database
         */
        if(!$conn->select_db(self::$name)) {
            echo "couldn't be able to select database \"" . self::$name . "\"\n";
            return false;
        }
        $conn->close();
        return true;
    }

    public static function drop() {
        /**
         * dropping database
         */
        $conn = new mysqli(self::$host, self::$user, self::$pass);
         if($stmt = $conn->prepare("DROP DATABASE IF EXISTS " . self::$name)) {
            if(!$stmt->execute()) {
                echo "couldn't drop database " . self::$name . "\n";
                $stmt->close();
                return false;
            }
            $stmt->close();
        } else {
            echo "couldn't create statement for dropping database\n";
            $conn->close();
            return false;
        }

        /**
         * closing connection
         */
        $conn->close();
        return true;
    }
}

?>