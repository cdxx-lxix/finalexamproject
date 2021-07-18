<?php
    $CurrentTitle = 'New Post';
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
            <div class="card MyCreator">
                <h3>Create new post</h3>
                <p>Please make sure you read and understood our rules before posting something</p>
                <p><b>Fields with * are required!</b></p>
                <hr>
                <form method="POST" action="processors/postProcessor.php" class="new-post-form" enctype="multipart/form-data">
                    <input type="text" name="title" maxlength="255" placeholder="Post's title *" required class="new-post-field">
                    <input type="hidden" name="author" value="<?php echo $_SESSION['username']; ?>">
                    <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                    <textarea name="content" cols="170" rows="30" placeholder="Your text goes here *" class="new-post-field"></textarea>
                    <div class="new-post-lower">

                        <label for="image" id="cnp-upload">Post's image: <input type="file" name="image"></label>
                        <label for="cats">Category:</label><select id="cats" name="category">
                            <?php
                            require_once 'processors/dbcu.php';
                            $sql = "SELECT * FROM categories";
                            $statement = mysqli_stmt_init($connection);
                            // If something gone wrong redirect back. ERRORCODE = beetle
                            if (!mysqli_stmt_prepare($statement, $sql)) {
                                header("location: " . $_SERVER['HTTP_REFERER'] . "?error=beetle");
                                exit();
                            }
                            mysqli_stmt_execute($statement);
                            // Categories table can't be empty so no check
                            $result = mysqli_stmt_get_result($statement);
                            mysqli_stmt_close($statement);
                            while($cats = mysqli_fetch_assoc($result)) {
                                echo "<option value=" . " ' " . $cats['CAT_ID'] . " ' " . ">" . $cats['CAT_NAME'] . " </option>";
                            }
                            ?>
                        </select>
                        <input type="submit" name="post" class="btn btn-primary cnp-buttons" value="Submit">
                        <a href="index.php" class="btn btn-warning cnp-buttons">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <?php include 'elements/rightColumn.php'?>
    </div>
</body>

<?php
    include 'elements/footer.php';
?>