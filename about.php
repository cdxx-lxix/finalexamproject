<?php
    $CurrentTitle = 'About';
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
                <div class="card">
                    <h2>About this place</h2>
                    <hr>
                    <p>Nothing extraordinary here. It's just a pretty basic blog made entirely by me in 5 days.</p>
                    <p><b>Languages:</b> HTML, CSS, PHP, JS, MySQL</p>
                    <p><b>Libraries and technologies:</b> AJAX, jQuery, Bootstrap, FontAwesome, PHPMailer, Dotenv, Composer, Node.js</p>
                    <p><b>Software:</b> PHPStorm, XAMPP, Github Desktop</p>
                </div>
                <div class="card">
                    <h2>Contact me</h2>
                    <hr>
                    <form action="processors/contactProcessor.php" style="width: 50%" method="POST">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" required placeholder="name@example.com" name="sender">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Message</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" required rows="3" name="message"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary mb-3" name="contact">Send</button>
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