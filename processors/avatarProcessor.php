<?php

require_once 'dbcu.php';
require_once 'fcu.php';

if(isset($_POST["upload-avatar"]) && !empty($_FILES["avatar"]["name"])) {

    $targetDir = "C:/XAMPP/htdocs/Finalsproject/images/";
    $temp = explode(".", $_FILES["avatar"]["name"]);
    $newfilename = round(microtime(true)) . '.' . end($temp);
    $targetFilePath = $targetDir . $newfilename;
    move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath);
    session_start();
    imageRegister($connection, $newfilename, $_SESSION["username"]);
    basicRedirector();

} else {
    errorRedirector('void');
}