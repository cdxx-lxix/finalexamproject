<?php
$CurrentTitle = 'Search results';
require_once 'processors/dbcu.php';
require_once 'processors/fcu.php';
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

            if (isset($_GET['query'])) {
                $search = $_GET['query'];
                $search = "%$search%";
                $sql = "SELECT * FROM posts WHERE POST_TITLE LIKE ? ORDER BY POST_ID DESC";
                $statement = mysqli_stmt_init($connection);
                // If something gone wrong redirect back. ERRORCODE = beetle
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    errorRedirector('beetle');
                }
                mysqli_stmt_bind_param($statement, "s", $search);
                mysqli_stmt_execute($statement);
                $resultData = mysqli_stmt_get_result($statement);
                while ($results = mysqli_fetch_assoc($resultData)) {
                    postFormatter($connection, $results['POST_CATEGORY'], $results['POST_TITLE'], $results['POST_AUTHOR'], $results['POST_DATE'], $results['POST_CONTENT'], $results['POST_ID'], $results['POST_IMAGE']);
                }
                if (empty($results)) {
                    echo "<div class='card'>";
                        echo "<div class='card-header'>";
                            echo "<h2 class='card-title'>That's all what was found</h2>";
                        echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='card'>";
                    echo "<div class='card-header'>";
                        echo "<h2 class='card-title'>No query. What did you expect to see here?</h2>";
                    echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

        <?php include 'elements/rightColumn.php'?>
    </div>
    </body>


<?php
include 'elements/footer.php';
?>