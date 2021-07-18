<?php

require_once 'C:/XAMPP/htdocs/Finalsproject/processors/dbcu.php';
if (isset($_POST["delete_id"])) {
    $sql = "DELETE FROM users WHERE UID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "i", $_POST["delete_id"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

if (isset($_POST['adminEdit'])) {
    $sql = "UPDATE users SET USERNAME = ?, REALNAME = ?, EMAIL = ?, BIRTHDAY = ? WHERE UID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "ssssi", $_POST["username"], $_POST["realname"], $_POST["email"], $_POST["birthday"], $_POST["uid"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

if (isset($_POST['adminPromote'])) {
    $sql = "INSERT INTO admins (ADMIN_UID, ADMIN_USERNAME) VALUES (?, ?)";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "is",$_POST["uid"], $_POST["username"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

if (isset($_POST['adminDemote'])) {
    $sql = "DELETE FROM admins WHERE ADMIN_UID = ? AND ADMIN_USERNAME = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "is",$_POST["uid"], $_POST["username"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}