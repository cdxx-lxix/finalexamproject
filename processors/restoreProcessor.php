<?php
    if (isset($_POST["submit"])) {
        $email = $_POST["email"];

        require_once 'dbcu.php';
        require_once 'fcu.php';

        //Check if username has some forbidden symbols. ERRORCODE = ghost
        if (checkEmail($connection, $email) === false) {
            errorRedirector('ghost');
        } else {
            $selector = bin2hex(random_bytes(8));
            $token = random_bytes(32);
            $url = "http://localhost:63342/Finalsproject/restoration.php?selector=" . $selector . "&validator=" . bin2hex($token);
            $expires = date("U") + 1800;
            deletePreviousToken($connection, $email);
            insertResetToken($connection, $email, $selector, $token, $expires);
            sendMail($email, $url, 'none', 'token');
            exit();

        }
    } else {
        errorRedirector('fox');
    }

