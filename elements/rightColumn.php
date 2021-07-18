<div class="rightcolumn">

    <!-- Mini profile box -->
    <div class="card MyMiniProfile">
        <?php
            if (isset($_SESSION['username'])) {
                $avatar = 'images/' . $_SESSION["user_img"];
                echo "<h2>Welcome, <i>" . $_SESSION['username'] .  "</i></h2>";
                echo "<p>Have a good stay</p>";
                echo "<ul class='list-group'>";
                echo "<img alt='' class='list-group-item' src='$avatar'>";
                echo "<a class='list-group-item' href='profile.php' title='Your profile'>Profile</a>";
                if ($_SESSION['isAdmin'] === true) {
                    echo "<a class='list-group-item' href='control.php' title='Admin panel'>Admin panel</a>";
                } else {
                    echo "<a class='list-group-item' href='help.php' title='Get help'>Get help</a>";
                }
                // echo "<a class='list-group-item' href='#' title='Settings'>Settings</a>";   Sadly i got no time to implement it
                echo "<a class='list-group-item' href='processors/logoutProcessor.php' title='Sign out'>Sign Out</a>";
                echo "</ul>";
            } else {
                echo "<h2>Welcome, guest</h2>";
                echo "<p>Please <a href='registration.php'>Sign up</a> or <b>Sign in</b> to get full access!</p>";
                echo "<form action='processors/authProcessor.php' class='MyAuth' method='POST'>
                    <input type='text' name='username' required placeholder='Username'>
                    <input type='password' name='password' required placeholder='Password'>
                    <input type='submit' name='sign_in' value='Sign in' class='btn btn-primary'>
                    <a href='restoration.php' style='text-align: center'>Forgot my password</a>
                   </form>";
            }
        ?>
    </div>

    <!-- Search box -->
    <div class="card">
        <h3>Search posts</h3>
        <form class="MySearch" method="GET" action="search.php">
            <input class="form-control mr-sm-2 search-box" type="search" required placeholder="Titles only" aria-label="Search" name="query" value="">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Go</button>
        </form>
    </div>

    <!-- Categories -->
    <div class="card">
        <h3>Categories</h3>
        <ul class="list-group">
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
            echo "<a class='list-group-item' href='index.php?filter=" . $cats['CAT_ID'] . "'>" . $cats['CAT_NAME'] . "</a>";
        }
        ?>
        </ul>
    </div>

    <!-- Social links box -->
    <div class="card MySoc">
        <h3>Social links</h3>
        <a href="https://vk.com/catcatskitties"><i class="fab fa-vk fa-2x"></i></a>
        <a href="https://github.com/cdxx-lxix"><i class="fab fa-github fa-2x"></i></a>
        <a href="https://steamcommunity.com/id/moonbeambitch/"><i class="fab fa-steam fa-2x"></i></a>
    </div>

</div>