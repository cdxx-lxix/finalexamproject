<?php

require_once 'dbcu.php';
require_once 'fcu.php';

if (isset($_POST['post'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $date = $_POST['date'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $image = imageRemake($_FILES["image"]["name"],$_FILES["image"]["tmp_name"]);
    createPost($connection, $title, $author, $content, $image, $date, $category);
    errorRedirector('child');

} elseif (isset($_POST['userDelete'])) {
    deletePost($connection, $_POST['post_id']);
} elseif (isset($_POST['userEdit'])) {
    $newImage = imageRemake($_FILES['post_image']['name'], $_FILES['post_image']['tmp_name']);
    editPost($connection, $_POST['post_id'], $_POST['post_title'], $_POST['post_content'], $newImage);
} else {
    errorRedirector('fox');
}