<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="author" content="Alex Mladich">
    <meta name="description" content="Web-developer course final project">
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="css/mainstyle.css" type="text/css">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <title><?php echo $CurrentTitle; ?></title>
    <script src="https://kit.fontawesome.com/13d442a1ee.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<div class="menu" id="menu_element">
    <a id="<?php echo $CurrentTitle == "Home Page" ? 'current' : '' ?>" href="index.php">Home</a>
    <a class="<?php if (!isset($_SESSION['username'])) { echo 'nav-link disabled'; } ?>" id="<?php echo $CurrentTitle == "New Post" ? 'current' : '' ?>" href="create_new_post.php">New Post</a>
    <a id="<?php echo $CurrentTitle == "About" ? 'current' : '' ?>" href="about.php">About</a>
    <a id="<?php echo $CurrentTitle == "Help" ? 'current' : '' ?>" href="help.php">Help</a>
</div>
