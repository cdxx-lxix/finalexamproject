<?php
$CurrentTitle = 'Your profile';
include 'elements/header.php';
?>

    <body>
    <?php
    include 'elements/errors.php';
    if (isset($_GET['error'])) {
        errorChecker($_GET['error']);
    }
    ?>
    <div class="row">
        <?php include 'elements/leftScroll.php'?>

        <?php include 'elements/leftProfile.php'?>

        <?php include 'elements/rightColumn.php'?>
    </div>
    </body>

<?php
include 'elements/footer.php';
?>