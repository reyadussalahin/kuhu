<?php

require_once("database/database.php");

require_once("utils/time/time.php");
require_once("utils/user/userID.php");

// register new user
// note: one must use unique username and email
//       if 'username' already exists, then that is checked by 'validateUsername' method
//       and for 'email', checked by 'validateEmail'
function register($firstName, $lastName, $username, $email, $password) {
    $conn = Database::getConnection();

    $registerDatetime = Time::bangladeshDateTime();
    $userID = UserID::get();
    // if userID doesn't contain space, then it is valid userID
    // otherwise, it's just an error
    if(strpos($userID, " ") !== false) {
        return $userID; // returning the error to print
    }

    $sql = "INSERT INTO user (id, first_name, last_name, username, email, password, register_datetime) VALUES (?, ?, ?, ?, ?, ?, ?)";
    if(!($stmt = $conn->prepare($sql))) {
        $conn->close();
        return "couldn't prepare statement for registering user";
    }
    $stmt->bind_param("sssssss", $userID, $firstName, $lastName, $username, $email, $password, $registerDatetime);
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "couldn't execute statement for registering user";
    }

    // if all operation is a success, then
    // increment userID, so that the next user
    // can have a new userID
    UserID::increment();

    // close everything
    $stmt->close();
    $conn->close();

    return true;
}
?>