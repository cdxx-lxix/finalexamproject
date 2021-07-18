<?php
    require_once 'dbcu.php';
    require_once 'fcu.php';
    session_start();
    // Case of password reset
    if (isset($_POST['submit'])) {
        $selector = $_POST['selector'];
        $validator = $_POST['validator'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        //Check if passwords matched ERRORCODE = drum
        if (noMatch($password1, $password2) !== false) {
            errorRedirector('drum');
        }

        // Location, selection and preparation to verify
        $currentDate = date("U");
        $sql = "SELECT * FROM restoration WHERE P_RESET_SELECTOR = ? AND P_RESET_EXPIRATION >= ?";
        $statement = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            errorRedirector('beetle');
        }
        mysqli_stmt_bind_param($statement, "ss", $selector, $currentDate);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        if (!$row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($statement);
            errorRedirector('retry');
        }
        mysqli_stmt_close($statement);

        // Verification and change stage
        $tokenBin = hex2bin($validator);
        $tokenCheck = password_verify($tokenBin, $row['P_RESET_TOKEN']);

        if ($tokenCheck === false) {
            errorRedirector('retry');
        } else {
            $tokenEmail = $row['P_RESET_EMAIL'];
            $sql = "SELECT * FROM users WHERE EMAIL = ?";
            $statement = mysqli_stmt_init($connection);
            if (!mysqli_stmt_prepare($statement, $sql)) {
                errorRedirector('beetle');
            }
            mysqli_stmt_bind_param($statement, "s", $tokenEmail );
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if (!$row = mysqli_fetch_assoc($result)) {
                mysqli_stmt_close($statement);
                errorRedirector('retry');
            }
            mysqli_stmt_close($statement);
            updatePassword($connection, $tokenEmail, $password1);
            deletePreviousToken($connection, $tokenEmail);
            header("location: ../index.php?error=change");
        }

        // Case of password change in the profile
    } elseif (isset($_POST['viaForm'])) {

        if (checkPassword($connection, $_SESSION['username'], $_POST['password_old'])) {
            // Making the change
            $sql = "UPDATE users SET PASSWORD = ? WHERE USERNAME = ?";
            $statement = mysqli_stmt_init($connection);
            if (!mysqli_stmt_prepare($statement, $sql)) {
                echo "beetle";
            }
            $hashPassword = password_hash($_POST['password_new1'], PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($statement, "ss",$hashPassword, $_SESSION['username']);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
            $result = mysqli_stmt_get_result($statement);
            echo "change";
        }
        // Case of info change in the profile
    } elseif (isset($_POST['viaProfile'])) {
        $sql = "UPDATE users SET REALNAME = ?, EMAIL = ?, BIRTHDAY = ? WHERE USERNAME = ?";
        $statement = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            echo "beetle";
        }
        mysqli_stmt_bind_param($statement, "ssss",$_POST['realname'], $_POST['email'], $_POST['birthday'], $_POST['username']);
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
        $_SESSION["realname"] = $_POST['realname'];
        $_SESSION["birthday"] = $_POST['birthday'];
        $_SESSION["email"] = $_POST['email'];
        echo "person";
    } else {
        errorRedirector('fox');
    }

