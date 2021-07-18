<?php

require_once 'C:\XAMPP\htdocs\Finalsproject\processors\dbcu.php';
require_once 'C:\XAMPP\htdocs\Finalsproject\processors\fcu.php';

if (isset($_POST["delete_id"])) {
    $sql = "DELETE FROM categories WHERE CAT_ID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "i", $_POST["delete_id"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

if (isset($_POST['adminEdit'])) {
    $sql = "UPDATE categories SET CAT_NAME = ? WHERE CAT_ID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "si", $_POST["cat_name"],$_POST["cat_id"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

if (isset($_POST['adminAdd'])) {
    $sql = "INSERT INTO categories (CAT_NAME) VALUES (?)";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "s", $_POST["cat_name"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}