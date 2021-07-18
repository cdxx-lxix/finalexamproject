<?php

require_once 'C:/XAMPP/htdocs/Finalsproject/processors/dbcu.php';
require_once 'C:/XAMPP/htdocs/Finalsproject/processors/fcu.php';

if (isset($_POST["delete_id"])) {
    $sql = "DELETE FROM posts WHERE POST_ID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "i", $_POST["delete_id"]);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

if (isset($_POST['adminPreEdit'])) {
    $sql = "SELECT * FROM posts WHERE POST_ID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "i", $_POST["edit_id"]);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    $row = mysqli_fetch_assoc($resultData);
    echo json_encode($row);
}

if (isset($_POST['adminEdit'])) {
    $newImage = imageRemake($_FILES['post_image']['name'], $_FILES['post_image']['tmp_name']);
    $sql = "UPDATE posts SET POST_TITLE = ?, POST_CONTENT = ?, POST_DATE = ?, POST_AUTHOR = ?, POST_CATEGORY = ?, POST_IMAGE = ? WHERE POST_ID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "ssssssi", $_POST['post_title'], $_POST['post_content'], $_POST['post_date'], $_POST['post_author'], $_POST['post_cat'], $newImage, $_POST['post_id']);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    echo $_POST['post_id'];
}