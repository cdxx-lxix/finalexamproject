<?php

require_once 'processors/dbcu.php';
require_once 'processors/fcu.php';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$records_per_page = 9;
$offset = ($page - 1) * $records_per_page;
$count_sql = "SELECT COUNT(*) FROM posts";
$respond = mysqli_query($connection, $count_sql);
$total_rows = mysqli_fetch_array($respond)[0];
$total_pages = ceil($total_rows / $records_per_page);


$sql = "SELECT * FROM posts WHERE POST_AUTHOR = ? ORDER BY POST_ID DESC LIMIT $offset, $records_per_page";
$statement = mysqli_stmt_init($connection);
if (!mysqli_stmt_prepare($statement, $sql)) {
    errorRedirector("beetle");
}
mysqli_stmt_bind_param($statement, "s", $_SESSION['username']);
mysqli_stmt_execute($statement);
$resultData = mysqli_stmt_get_result($statement);
while ($posts = mysqli_fetch_assoc($resultData)) {
$current_cat = displayHumanCategory($connection, $posts['POST_CATEGORY']);
echo "<div class='col'>";
echo "<div class='card MyProfileCards' >";
echo "<input class='img-edit' hidden value=" .$posts['POST_IMAGE'] . ">";
    echo "<img alt='' class='card-img-top MyPostMinipic' " . "src=". "images/" . $posts['POST_IMAGE'] .  ">";
    echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . $posts['POST_TITLE'] . "</h5>";
        echo "<p class='card-text text-truncate'>" . $posts['POST_CONTENT'] . "</p>>";
    echo "</div>";
    echo "<ul class='list-group list-group-flush'>";
        echo "<li class='list-group-item'>" . "Category: " . "<b class='cat'>" . $current_cat . "</b>" . "</li>";
        echo "<li class='list-group-item'>" . "Date: " . "<b>" . $posts['POST_DATE'] . "</b>" . "</li>";
    echo "</ul>";
    echo "<div class='card-body btn-group MyMiniPost' role='group'>";
        echo "<a href='posts.php?id=" . $posts['POST_ID'] . "' class='btn btn-secondary'>View</a>";
        echo "<button class='btn btn-warning' data-toggle='modal' data-target='#post-edit' onclick='preEdit(this.parentNode.parentNode, this.value)' value='" . $posts['POST_ID'] . "'>Edit</button>";
        echo "<button class='btn btn-danger' onclick='deletePost(this)' value='" . $posts['POST_ID'] . "'>Delete</button>";
    echo "</div>";
echo "</div>";
echo "</div>";
}
?>

<!-- Post edit modal window -->
<div class="modal fade" id="post-edit" tabindex="-1" aria-labelledby="post-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editing post: <b class="display-title"></b></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="text" class="edit-title form-control edit-window-element" value="">
                    <textarea type="text" class="edit-content form-control edit-window-element" rows="10"></textarea>
                    <img src="" alt="" class="edit-display img-thumbnail rounded mx-auto d-block edit-window-element">
                    <div class="mb-3 edit-window-element">
                        <label for="formFile" class="form-label">Select new thumbnail</label>
                        <input class="form-control edit-image" type="file" id="formFile">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-id" value="" onclick='editPost(this.parentNode.parentNode, this.value)'>Save changes</button>
            </div>
        </div>
    </div>
</div>
