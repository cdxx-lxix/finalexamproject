<?php
    $CurrentTitle = 'Password restoration';
    include 'elements/header.php';

    if (isset($_SESSION['username'])) {
        // Returns a logged in user back to their profile with a message
        header("location: ./profile.php?error=sheep");
        exit();
    }
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

        <?php
            if (!empty($_GET['selector'] && !empty($_GET['validator']))) {
                include 'elements/leftChange.php';
            } else {
                include 'elements/leftRestoration.php';
            }
        ?>

        <?php include 'elements/rightColumn.php'?>
    </div>
    </body>

<?php
    include 'elements/footer.php';
?>