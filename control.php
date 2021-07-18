<?php
$CurrentTitle = 'Admin Panel';
include 'elements/header.php';
if ($_SESSION['isAdmin'] !== true) {
    header("location: index.php?error=fox");
    exit();
}
?>
    <link rel="stylesheet" href="css/adminPanel.css">
    <body>
    <?php
    include 'elements/errors.php';
    if (isset($_GET['error'])) {
        errorChecker($_GET['error']);
    }
    ?>
    <div class="row">
        <?php include 'elements/leftScroll.php'?>

        <?php
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'Posts':
                    include 'admin/adminPosts.php';
                    break;
                case 'Categories':
                    include 'admin/adminCategories.php';
                    break;
                case 'Users':
                    include 'admin/adminUsers.php';
                    break;
            }
        } else {
            include 'admin/adminControl.php';
        }
        ?>

        <?php include 'elements/rightColumn.php'?>
    </div>
    </body>

<?php
include 'elements/footer.php';
?>