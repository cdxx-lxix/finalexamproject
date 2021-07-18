<?php
$CurrentTitle = $_GET['title'];
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

        <div class="leftcolumn">
            <?php

            require_once 'processors/dbcu.php';
            require_once 'processors/fcu.php';

            $sql = "SELECT * FROM posts WHERE POST_ID = ?";
            $statement = mysqli_stmt_init($connection);
            // If something gone wrong redirect back. ERRORCODE = beetle
            if (!mysqli_stmt_prepare($statement, $sql)) {
                header("location: index.php?error=beetle");
                exit();
            }
            mysqli_stmt_bind_param($statement, "i", $_GET['id']);
            mysqli_stmt_execute($statement);
            $resultData = mysqli_stmt_get_result($statement);
            while ($results = mysqli_fetch_assoc($resultData)) {
                postFormatter($connection, $results['POST_CATEGORY'], $results['POST_TITLE'], $results['POST_AUTHOR'], $results['POST_DATE'], $results['POST_CONTENT'], $results['POST_ID'], $results['POST_IMAGE'], 'card-text-full', false);
            }
            ?>
        </div>


        <?php include 'elements/rightColumn.php'?>
    </div>
    </body>

<?php
include 'elements/footer.php';
?>