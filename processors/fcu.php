<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\XAMPP\htdocs\Finalsproject\vendor\autoload.php';

//FCU - Functions Containing Unit

/**
 * @param $target - target to check for bad symbols.
 * @return bool TRUE - bad symbols, FALSE - no bad symbols
 */
function BadSymbols($target) {
    $result = null;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $target)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

/**
 * @param $first - first operand
 * @param $second - second operand
 * @return bool - (IDENTICAL) TRUE - no match. FALSE - match.
 */
function noMatch($first, $second) {
    $result = null;
    if ($first !== $second) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

/**
 * @param $connection - SQL connection
 * @param $username
 * @return false|string[]|null - TRUE returns both true and an sql array, FALSE returns just false.
 */
function notUnique($connection, $username) {
    // Prepares a statement to protect db from injections
    $sql = "SELECT * FROM users WHERE USERNAME = ?";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);

    //Saved for later usage in login system too.
    if ($row = mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($statement);
        return $row;
    } else {
        mysqli_stmt_close($statement);
        return false;
    }
}

// Creates a new entry in SQL db
function registerNewUser($connection, $username, $password, $realname, $email, $birthday) {
    $sql = "INSERT INTO users (USERNAME, PASSWORD, REALNAME, EMAIL, BIRTHDAY) VALUES (?, ?, ?, ?, ?)";
    $statement = mysqli_stmt_init($connection);
    // If sign up gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    } else {
        // Protection motherfucker, do you hash it?
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($statement, "sssss", $username, $hashPassword, $realname, $email, $birthday);
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
        errorRedirector('none');
    }
}

// Obviously logins a user
function loginUser($connection, $username, $password) {
    $existence_check = notUnique($connection, $username);
    if ($existence_check === false) {
        errorRedirector('fire');
    }

    $passwordHashed = $existence_check["PASSWORD"];
    $check_password = password_verify($password, $passwordHashed);

    if ($check_password === false) {
        errorRedirector('fire');
    } else {
        session_start();
        $_SESSION["user_id"] = $existence_check["UID"];
        $_SESSION["username"] = $existence_check["USERNAME"];
        $_SESSION["realname"] = $existence_check["REALNAME"];
        $_SESSION["birthday"] = $existence_check["BIRTHDAY"];
        $_SESSION["email"] = $existence_check["EMAIL"];
        $_SESSION['user_img'] = getAvatar($connection, $username);
        $_SESSION['isAdmin'] = isAdmin($connection, $_SESSION["user_id"], $_SESSION["username"]);
    }
}

function checkEmail($connection, $email) {
    $sql = "SELECT * FROM users WHERE EMAIL = ?";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);

    //Saved for later usage
    if ($row = mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($statement);
        return $row;
    } else {
        mysqli_stmt_close($statement);
        return false;
    }
}

/**
 * Deletes all previous tokens for this specific user
 * @param $connection - db connector
 * @param $email - user email
 */
function deletePreviousToken($connection, $email) {
    $sql = "DELETE FROM restoration WHERE P_RESET_EMAIL = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

/**
 * Creates a new entry in the RESTORATION db table after user's successful restore request
 * @param $connection - db connector
 * @param $email - user entered email
 * @param $selector - generated selector
 * @param $token - generated unbin2hexed token
 * @param $expiration - date of token expiration
 */
function insertResetToken($connection, $email, $selector, $token, $expiration) {
    $sql = "INSERT INTO restoration (P_RESET_EMAIL, P_RESET_SELECTOR, P_RESET_TOKEN, P_RESET_EXPIRATION) VALUES (?, ?, ?, ?)";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    $hasedToken = password_hash($token, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($statement, "ssss", $email, $selector, $hasedToken, $expiration);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function sendMail($sender, $url, string $message, string $purpose) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $smtp_mail = $_ENV['SMTP_MAIL'];
    $smtp_password = $_ENV['SMTP_PASSWORD'];
    $recipient = $_ENV['SMTP_RECIPIENT'];
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug =1;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->Host = "smtp.gmail.com";
    $mail->Username = $smtp_mail;
    $mail->Password = $smtp_password;
    $mail->IsHTML(true);
    switch ($purpose) {
        case 'token':
            try {
                $mail->AddAddress($sender, "Dear user of Finals project");
                $mail->SetFrom($smtp_mail, "Finals Project");
                $mail->Subject = "Password recovery for Finals Project";
                $mail->Body = "<p>We received a password reset request. Here is your link: <a href=" . $url . ">" . $url . "</a> </p>";
                $mail->send();
                errorRedirector('message');
            } catch (Exception $e) {
                echo $e->errorMessage();
            }
            break;

        case 'contact':
            try {
                $mail->AddAddress($recipient, "Finals project user");
                $mail->SetFrom($smtp_mail, "User: $sender");
                $mail->Subject = "Question from Final project user";
                $mail->Body = $message;
                $mail->send();
            } catch (Exception $e) {
                echo $e->errorMessage();
            }
    }
}


/**
 * Used in restoration and profile page
 * @param $connection - db connector
 * @param $identifier - email or username
 * @param $new_password - new password
 */
function updatePassword($connection, $identifier, $new_password) {
    $sql = "UPDATE users SET PASSWORD = ? WHERE EMAIL = ? OR USERNAME = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($statement, "sss", $new_password_hashed, $identifier, $identifier);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}


/**
 * If you add a owner the image becomes an avatar in other cases it's just an image
 * @param $connection - db connector
 * @param $imageName - just the name without path
 * @param null $owner - (optional) for avatar
 */
function imageRegister($connection, $imageName, $owner=null) {
    $sql = "INSERT INTO images (IMAGE_NAME, IMAGE_AVATAR_FK) VALUES (?, ?)";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    } else {
        mysqli_stmt_bind_param($statement, "ss", $imageName, $owner);
        if ($owner !== null) {
            deletePreviousAvatar($connection, $owner);
            session_start();
            $_SESSION["user_img"] = $imageName;
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
    }
}

function getAvatar($connection, $username) {
    $sql = "SELECT IMAGE_NAME FROM images WHERE IMAGE_AVATAR_FK = ?";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }

    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    if ($image = mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($statement);
        return $image['IMAGE_NAME'];
    } else {
        mysqli_stmt_close($statement);
        return "noAvatar.jpg";
    }
}

function deletePreviousAvatar($connection, $username) {
    $sql = "DELETE FROM images WHERE IMAGE_AVATAR_FK = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function isAdmin($connection, $uid, $username) {
    $sql = "SELECT ADMIN_ID FROM admins WHERE ADMIN_UID = ? AND ADMIN_USERNAME = ?";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "ss", $uid,$username);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    if ($adminID = mysqli_fetch_object($resultData)) {
        mysqli_stmt_close($statement);
        return true;
    } else {
        mysqli_stmt_close($statement);
        return false;
    }
}

function imageRemake($image, $image_tmp) {
    if (!empty($image)) {
        $targetDir = "C:/XAMPP/htdocs/Finalsproject/images/";
        $temp = explode(".", $image);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $targetFilePath = $targetDir . $newfilename;
        move_uploaded_file($image_tmp, $targetFilePath);
        return $newfilename;
    } else {
        return 'no_image.jpg';
    }
}

function createPost($connection, $title, $author, $content, $image, $date, $category) {
    $sql = "INSERT INTO posts (POST_AUTHOR, POST_TITLE, POST_IMAGE, POST_CONTENT, POST_DATE, POST_CATEGORY) VALUES (?, ?, ?, ?, ?, ?)";
    $statement = mysqli_stmt_init($connection);
    // If something wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "sssssi", $author, $title, $image, $content, $date, $category);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

}

function displayHumanCategory($connection, $cat_id) {
    $sql = "SELECT CAT_NAME FROM categories WHERE CAT_ID = ?";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_bind_param($statement, "s", $cat_id);
    mysqli_stmt_execute($statement);
    // Categories table can't be empty so no check
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    $the_cat = mysqli_fetch_assoc($result);
    return $the_cat['CAT_NAME'];
}

function errorRedirector(string $error) {
    // For full list of errors look in errors.php
    if (substr($_SERVER['HTTP_REFERER'], -4) == '.php') {
        header("location: " . $_SERVER['HTTP_REFERER'] . "?error=$error");
        exit();
    } else {
        header("location: " . $_SERVER['HTTP_REFERER'] . "&error=$error");
        exit();
    }
}

function basicRedirector() {
    // Redirects to previous page with all of url arguments
    header("location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

function clearRedirector() {
    // Redirects to previous page without arguments
    if (substr($_SERVER['HTTP_REFERER'], -4) == '.php') {
        header("location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $clearLocation = substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], "?"));
        header("location: " . $clearLocation);
        exit();
    }
}

function checkPassword($connection, $username, $password) {
    $existence_check = notUnique($connection, $username);
    $passwordHashed = $existence_check["PASSWORD"];
    return password_verify($password, $passwordHashed);
}

function deletePost($connection, $postID) {
    $sql = "DELETE FROM posts WHERE POST_ID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        echo "beetle";
    }
    mysqli_stmt_bind_param($statement, "s", $postID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    echo "dead";
}

function editPost($connection, $post_id, $post_title, $post_content, $post_image) {
    $sql = "UPDATE posts SET POST_TITLE = ?, POST_CONTENT = ?, POST_IMAGE = ? WHERE POST_ID = ?";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        echo "beetle";
    }
    mysqli_stmt_bind_param($statement, "ssss", $post_title, $post_content, $post_image, $post_id);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    echo "reborn";
}

/**
 * Displays post in a proper way
 * @param $connection - db connection
 * @param $category - post category ID
 * @param $title - post title
 * @param $author - post author
 * @param $date - post date
 * @param $content - post content
 * @param $id - post ID
 * @param $image - post image
 * @param string $fulltext : card-text (default) covers text / card-text-full shows full text
 * @param bool $showFooter : true (default) shows footer / false covers it
 */
function postFormatter($connection, $category, $title, $author, $date, $content, $id, $image, string $fulltext = 'card-text', bool $showFooter = true) {
    $current_cat = displayHumanCategory($connection, $category);
    echo "<div class='card'>";
        echo "<div class='card-header' style='background-color: #fff'>";
            echo "<h2 class=''>$title</h2>";
            echo "<h5 class='card-title'>Author: <a href='index.php?filter=$author'>$author</a> | Posted on: <a href='index.php?filter=$date'>$date</a> | Category: <a href='index.php?filter=$category'>$current_cat</a></h5>";
        echo "</div>";

        echo "<div class='card-body'>";
            echo "<img alt='' class='card-img-top' src='images/$image'>";
            echo "<hr>";
            echo "<p class='$fulltext'>$content</p>";
        echo "</div>";
        if ($showFooter == true) {
            echo "<a class='full-version-button' href='posts.php?id=$id&title=$title'>";
            echo "<div class='card-footer text-lg-center'><h3>See full version<h3></div>";
            echo "</a>";
        }
    echo "</div>";
}

function paginationFormatter($connection, int $recordsPerPage, string $table, int $page) {
    if (isset($_GET['filter'])) {
        $previous = '?filter='. $_GET['filter'] . '&page=' . ($page - 1);
        $next = '?filter='. $_GET['filter'] . '&page=' . ($page + 1);
        $prefix = '?filter='. $_GET['filter'] . '&page';
    }  else {
        $previous = '?page=' . ($page - 1);
        $next = '?page=' . ($page + 1);
        $prefix = '?page';
    }

    $count_sql = "SELECT COUNT(*) FROM $table";
    $respond = mysqli_query($connection, $count_sql);
    $total_rows = mysqli_fetch_array($respond)[0];
    $total_pages = ceil($total_rows / $recordsPerPage);

    $disableBtn = 'disabled';
    $nothing = '';
    $sharp = '#';
    $isPreviousDisabled = $page <= 1 ? $disableBtn : $nothing;
    $previousLink = $page <= 1 ? $sharp : $previous;
    $isNextDisabled = $page >= $total_pages ? $disableBtn : $nothing;
    $nextLink = $page >= $total_pages ? $sharp : $next;

    echo "<div class='card'>";
        echo "<ul class='pagination justify-content-center'>";
            echo "<li class='page-item'><a class='page-link' href='$prefix=1'>First</a></li>";
            echo "<li class='page-item $isPreviousDisabled'><a class='page-link' href='$previousLink'>Previous</a></li>";

            echo "<li class='page-item $isNextDisabled'><a class='page-link' href='$nextLink'>Next</a></li>";
            echo "<li class='page-item'><a class='page-link' href='$prefix=$total_pages'>Last</a></li>";
        echo "</ul>";
    echo "</div>";
}