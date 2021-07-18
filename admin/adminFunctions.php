<?php

require_once 'processors/fcu.php';

function getAllUsers($connection) {
    $sql = "SELECT * FROM users ORDER BY UID";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_execute($statement);
    // Users table can't be empty so there is no !empty check
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    while($users = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td class='uid'>" . $users["UID"] . "</td>";
        echo "<td class='username'>" . $users["USERNAME"] . "</td>";
        echo "<td class='realname'>" . $users["REALNAME"] . "</td>";
        echo "<td class='email'>" . $users["EMAIL"] . "</td>";
        echo "<td class='birthday'>" . $users["BIRTHDAY"] . "</td>";
        echo "<td class='isAdmin'>" . isAdmin($connection, $users["UID"], $users["USERNAME"]) . "</td>";
        echo "<td>" . "<button value=" . $users["UID"] . " class=\"btn btn-warning edit-user\" data-toggle='modal' data-target='#user-edit' name=\"edit\" type=\"submit\" onclick=\"preEditUser(this.parentNode.parentNode)\"><i class=\"far fa-edit fa-sm\"></i></button>" . "</td>";
        echo "<td>" . "<button value=" . $users["UID"] . " class=\"btn btn-danger delete-user\" name=\"delete\" type=\"submit\" onclick=\"deleteUser(this)\"><i class=\"far fa-trash-alt fa-sm\"></i></button>" . "</td>";
        echo "</tr>";
    }
}

function getAllCategories($connection) {
    $sql = "SELECT * FROM categories ORDER BY CAT_ID";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_execute($statement);
    // Category table can't be empty so there is no !empty check
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    while($cats = mysqli_fetch_assoc($result)) {
        $counter = countPosts($connection, $cats["CAT_ID"]);
        echo "<tr>";
        echo "<td class='cat-id'>" . $cats["CAT_ID"] . "</td>";
        echo "<td class='cat-name'>" . $cats["CAT_NAME"] . "</td>";
        echo "<td>" . $counter['COUNT(*)'] . "</td>";
        echo "<td>" . "<a href=\"index.php?filter=" . $cats['CAT_ID'] . "\" class='btn btn-info edit-user' type='submit'><i class='far fa-eye fa-sm'></i></a>" . "</td>";
        echo "<td>" . "<button class='btn btn-warning edit-user' data-toggle='modal' data-target='#cat-edit' name='edit' type='submit' onclick='preEditCategory(this.parentNode.parentNode)'><i class='far fa-edit fa-sm'></i></button>" . "</td>";
        echo "<td>" . "<button value=" . $cats["CAT_ID"] . " class='btn btn-danger delete-category' name='delete' type='submit' onclick='deleteCategory(this)'><i class='far fa-trash-alt fa-sm'></i></button>" . "</td>";
        echo "</tr>";
    }
}

function getAllPosts($connection, $offset, $perPage) {
    $sql = "SELECT * FROM posts ORDER BY POST_ID LIMIT $offset, $perPage";
    $statement = mysqli_stmt_init($connection);
    // If something gone wrong redirect back. ERRORCODE = beetle
    if (!mysqli_stmt_prepare($statement, $sql)) {
        errorRedirector('beetle');
    }
    mysqli_stmt_execute($statement);
    // Category table can't be empty so there is no !empty check
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    while($posts = mysqli_fetch_assoc($result)) {
        $current_cat = displayHumanCategory($connection, $posts['POST_CATEGORY']);
        echo "<tr>";
        echo "<td>" . $posts["POST_ID"] . "</td>";
        echo "<td>" . $posts["POST_TITLE"] . "</td>";
        echo "<td>" . $posts['POST_AUTHOR'] . "</td>";
        echo "<td>" . $posts['POST_IMAGE'] . "</td>";
        echo "<td>" . $posts['POST_DATE'] . "</td>";
        echo "<td>" . $current_cat . "</td>";
        echo "<td>" . "<a href=\"posts.php?id=" . $posts['POST_ID'] . "\" class='btn btn-info edit-user' type='submit'><i class='far fa-eye fa-sm'></i></a>" . "</td>";
        echo "<td>" . "<button value=" . $posts["POST_ID"] . " class='btn btn-warning edit-user' name='edit' type='submit' data-toggle='modal' data-target='#post-edit' onclick='preEditPost(this.value)'><i class='far fa-edit fa-sm'></i></button>" . "</td>";
        echo "<td>" . "<button value=" . $posts["POST_ID"] . " class='btn btn-danger delete-category' name='delete' type='submit' onclick='deletePost(this)'><i class='far fa-trash-alt fa-sm'></i></button>" . "</td>";
        echo "</tr>";
    }
}

function countPosts($connection, $category) {
    $sql = "SELECT COUNT(*) FROM posts WHERE POST_CATEGORY = $category";
    $temp = mysqli_query($connection, $sql);
    return mysqli_fetch_assoc($temp);
}