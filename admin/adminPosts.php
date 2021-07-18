<?php
require_once 'processors/dbcu.php';
require_once 'admin/adminFunctions.php';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$records_per_page = 50;
$offset = ($page - 1) * $records_per_page;
$count_sql = "SELECT COUNT(*) FROM posts";
$respond = mysqli_query($connection, $count_sql);
$total_rows = mysqli_fetch_array($respond)[0];
$total_pages = ceil($total_rows / $records_per_page);
?>

<div class="leftcolumn">
    <div class="card">
        <a href="control.php" class="btn btn-primary btn-back">Go back</a>
        <h2>List of Finals project categories:</h2>
        <h5>As of <?php echo date('d-m-Y'); ?></h5>
        <p>Here you can view and modify users of this resource as you wish but don't abuse your authority.</p>
        <form id="live-search" action="" class="styled" method="post">
            <input type="text" class="text-input" id="filter" placeholder="search" />
        </form>
        <table class="admin-users-table">
            <tr>
                <th>ID</th>
                <th>TITLE</th>
                <th>AUTHOR</th>
                <th>IMAGE</th>
                <th>DATE</th>
                <th>CATEGORY</th>
                <th>VIEW</th>
                <th>EDIT</th>
                <th>DELETE</th>
            </tr>
            <?php getAllPosts($connection, $offset, $records_per_page); ?>
        </table>
    </div>

    <div class="card">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="?action=Posts&page=1">First</a></li>
            <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>"><a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?action=Posts&page=".($page - 1); } ?>">Previous</a></li>

            <li class="page-item<?php if($page >= $total_pages){ echo 'disabled'; } ?>"><a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?action=Posts&page=".($page + 1); } ?>">Next</a></li>
            <li class="page-item"><a class="page-link" href="?action=Posts&page=<?php echo $total_pages; ?>">Last</a></li>
        </ul>
    </div>
</div>

<script src="javascript/admin.js"></script>


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
                    <div class="mb-3">
                        <label class="col-sm-2 col-form-label">CATEGORY:
                            <input type="number" class="post-category form-control edit-window-element form-control-sm" value="" min="0">
                        </label>
                        <label class="col-sm-2 col-form-label">TITLE:
                            <input type="text" class="post-title form-control edit-window-element form-control-sm" value="">
                        </label>
                        <label class="col-sm-2 col-form-label">DATE:
                            <input type="date" class="post-date form-control edit-window-element form-control-sm" value="">
                        </label>
                        <label class="col-sm-2 col-form-label">AUTHOR:
                            <input type="text" class="post-author form-control edit-window-element form-control-sm" value="">
                        </label>
                        <label class="col-sm-2 col-form-label">ID:
                            <input disabled type="text" class="post-id edit-window-element form-control-sm" max="5">
                        </label>
                    </div>
                    <textarea type="text" class="post-content form-control edit-window-element" rows="10"></textarea>
                    <img src="" alt="" class="post-display img-thumbnail rounded mx-auto d-block">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Select new thumbnail</label>
                        <input class="form-control post-image" type="file" id="formFile">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-id" value="" onclick='editPostAdmin(this.parentNode.parentNode)'>Save changes</button>
            </div>
        </div>
    </div>
</div>
