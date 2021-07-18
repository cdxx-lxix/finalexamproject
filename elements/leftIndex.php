<?php include 'elements/leftScroll.php'?>

<div class="leftcolumn">
    <?php

    require_once 'processors/dbcu.php';
    require_once 'processors/fcu.php';

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $records_per_page = 5;
    $offset = ($page - 1) * $records_per_page;


    if (isset($_GET['filter'])) {
        $sql = "SELECT * FROM posts WHERE POST_AUTHOR = ? OR POST_CATEGORY = ? OR POST_DATE = ? ORDER BY POST_ID DESC LIMIT $offset, $records_per_page";
        $statement = mysqli_stmt_init($connection);
        // If something gone wrong redirect back. ERRORCODE = beetle
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("location: index.php?error=beetle");
            exit();
        }
        mysqli_stmt_bind_param($statement, "sss", $_GET['filter'], $_GET['filter'], $_GET['filter']);
        mysqli_stmt_execute($statement);
        $resultData = mysqli_stmt_get_result($statement);
        while ($results = mysqli_fetch_assoc($resultData)) {
            postFormatter($connection, $results['POST_CATEGORY'], $results['POST_TITLE'], $results['POST_AUTHOR'], $results['POST_DATE'], $results['POST_CONTENT'], $results['POST_ID'], $results['POST_IMAGE']);
        }
    } else {
        $sql = "SELECT * FROM posts ORDER BY POST_ID DESC LIMIT $offset, $records_per_page";
        $statement = mysqli_stmt_init($connection);
        // If something gone wrong redirect back. ERRORCODE = beetle
        if (!mysqli_stmt_prepare($statement, $sql)) {
            errorRedirector('beetle');
        }
        mysqli_stmt_execute($statement);
        $resultData = mysqli_stmt_get_result($statement);
        while ($results = mysqli_fetch_assoc($resultData)) {
            postFormatter($connection, $results['POST_CATEGORY'], $results['POST_TITLE'], $results['POST_AUTHOR'], $results['POST_DATE'], $results['POST_CONTENT'], $results['POST_ID'], $results['POST_IMAGE']);
        }
    }

    paginationFormatter($connection, $records_per_page, 'posts', $page);
    ?>


</div>

