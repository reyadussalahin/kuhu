<?php

require_once("database/database.php");

// filters, validators etc...

// sql statement used:
// "CREATE TABLE IF NOT EXISTS user (
//     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     username VARCHAR(255),
//     email VARCHAR(255),
//     password VARCHAR(255),
//     first_name VARCHAR(255),
//     last_name VARCHAR(255)
// )"

const FIRST_NAME_MIN_LEN = 1;
const FIRST_NAME_MAX_LEN = 254;
const LAST_NAME_MIN_LEN = 1;
const LAST_NAME_MAX_LEN = 254;
const USERNAME_MIN_LEN = 3;
const USERNAME_MAX_LEN = 254;
const EMAIL_MAX_LEN = 254;
const PASSWORD_MIN_LEN = 8;
const PASSWORD_MAX_LEN = 254;

function firstNameMinLen() {
    return FIRST_NAME_MIN_LEN;
}
function firstNameMaxLen() {
    return FIRST_NAME_MAX_LEN;
}
function lastNameMinLen() {
    return LAST_NAME_MIN_LEN;
}
function lastNameMaxLen() {
    return LAST_NAME_MAX_LEN;
}
function usernameMinLen() {
    return USERNAME_MIN_LEN;
}
function usernameMaxLen() {
    return USERNAME_MAX_LEN;
}
function emailMaxLen() {
    return EMAIL_MAX_LEN;
}
function passwordMinLen() {
    return PASSWORD_MIN_LEN;
}
function passwordMaxLen() {
    return PASSWORD_MAX_LEN;
}


// validation methods
function validateFirstName($firstName) {
    $len = strlen($firstName);
    if($len < FIRST_NAME_MIN_LEN) return "first name should be at least " . FIRST_NAME_MIN_LEN  . " character(s) long";
    if($len > FIRST_NAME_MAX_LEN) return "first name can be at most " . FIRST_NAME_MAX_LEN . " character(s) long";
    return "";
}

function validateLastName($lastName) {
    $len = strlen($lastName);
    if($len < LAST_NAME_MIN_LEN) return "last name should be at least " . LAST_NAME_MIN_LEN  . " character(s) long";
    if($len > LAST_NAME_MAX_LEN) return "last name can be at most " . LAST_NAME_MAX_LEN . " character(s) long";
    return "";
}

function validateUsername($username) {
    $len = strlen($username);
    if($len < USERNAME_MIN_LEN) return "username should be at least " . USERNAME_MIN_LEN . " character(s) long";
    if($len > USERNAME_MAX_LEN) return "username can be at most " . USERNAME_MAX_LEN . " character(s) long";

    // username can not contain whitespaces
    for($i=0; $i<$len; $i++) {
        $ch = $username[$i];
        if($ch === " " || $ch === "\t" || $ch === "\n" || $ch === "\r") {
            return "username can not contain whitespace(s)";
        }
    }
    
    // check if username already taken
    $conn = Database::getConnection();
    $sql = "SELECT id from user WHERE username = ?";
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't create statement for searching username(inside validateUsername)";
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    if($stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return "username already taken. try a new username.";
    }
    $stmt->close();
    $conn->close();
    return "";
}

function validateEmail($email) {
    $len = strlen($email);
    if($len > EMAIL_MAX_LEN) return "email can be at most " . EMAIL_MAX_LEN . " character(s) long";

    // checking email format
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "email format not recognized";
    }

    // check if email already taken
    $conn = Database::getConnection();
    $sql = "SELECT id from user WHERE email = ?";
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't create statement for searching email(inside validateEmail)";
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    if($stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return "email already taken. try a new email.";
    }
    $stmt->close();
    $conn->close();
    return "";
}

function validatePassword($password) {
    $len = strlen($password);
    if($len < PASSWORD_MIN_LEN) return "password should be at least " . PASSWORD_MIN_LEN . " character(s) long";
    if($len > PASSWORD_MAX_LEN) return "password can be at most " . PASSWORD_MAX_LEN . " character(s) long";
    return "";
}

?>
