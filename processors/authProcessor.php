<?php
    session_start();
    require_once 'fcu.php';
    unset($_SESSION['Validator']);

    if (isset($_POST['sign_in'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        require_once 'DBCU.php';
        require_once 'FCU.php';

        loginUser($connection, $username, $password);
        clearRedirector();

    } else {
        basicRedirector();
    }