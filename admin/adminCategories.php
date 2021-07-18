<?php
require_once 'processors/dbcu.php';
require_once 'admin/adminFunctions.php';
?>

<div class="leftcolumn">
    <div class="card">
        <a href="control.php" class="btn btn-primary btn-back">Go back</a>
        <h2>List of Finals project categories:</h2>
        <h5>As of <?php echo date('d-m-Y'); ?></h5>
        <p>Here you can view and modify users of this resource as you wish but don't abuse your authority.</p>
        <form id="live-search" action="" class="styled" method="post">
            <input type="text" class="text-input" id="filter" placeholder="search" />
            <button type="button" class="btn btn-primary" style="float: right" data-toggle='modal' data-target='#cat-new'>Add new</button>
        </form>
        <table class="admin-users-table">
            <tr>
                <th>ID</th>
                <th>CATEGORY NAME</th>
                <th>POSTS</th>
                <th>VIEW</th>
                <th>EDIT</th>
                <th>DELETE</th>
            </tr>
            <?php getAllCategories($connection); ?>
        </table>
    </div>
</div>


<script src="javascript/admin.js"></script>

<!-- Category edit modal window -->
<div class="modal fade" id="cat-edit" tabindex="-1" aria-labelledby="cat-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editing category: <b class="edit-cat-title"></b></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="hidden" class="edit-cat-id" value="">
                    <input type="text" class="edit-cat-name form-control" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" value="" onclick='editCategory(this.parentNode.parentNode)'>Rename</button>
            </div>
        </div>
    </div>
</div>

<!-- New category modal window -->
<div class="modal fade" id="cat-new" tabindex="-1" aria-labelledby="cat-new" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New category</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="text" class="new-cat-name form-control" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" value="" onclick='addCategory(this.parentNode.parentNode)'>Add</button>
            </div>
        </div>
    </div>
</div>