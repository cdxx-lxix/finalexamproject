<?php

// If a user is trying to access this processor page in any other way than via signup page -> redirect to signup
if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password1"];
    $password_check = $_POST["password2"];
    $realname = $_POST["realname"];
    $email = $_POST["email"];
    $birthday = $_POST['birthday'];

    require_once 'dbcu.php';
    require_once 'fcu.php';

    //Check if username has some forbidden symbols. ERRORCODE = magic
    if (BadSymbols($username) !== false) {
        errorRedirector('magic');
    }

    //Check if passwords matched ERRORCODE = drum
    if (noMatch($password, $password_check) !== false) {
        errorRedirector('drum');
    }

    //Check if username is UNIQUE. ERRORCODE = violin
    if (notUnique($connection, $username) !== false) {
        errorRedirector('violin');
    }


    registerNewUser($connection, $username, $password, $realname, $email, $birthday);

} else {
    errorRedirector('fox');
}