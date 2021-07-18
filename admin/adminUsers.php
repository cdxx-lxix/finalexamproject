<?php
require_once 'processors/dbcu.php';
require_once 'admin/adminFunctions.php';
?>

<div class="leftcolumn">
    <div class="card">
        <a href="control.php" class="btn btn-primary btn-back">Go back</a>
        <h2>List of Finals project users:</h2>
        <h5>As of <?php echo date('d-m-Y'); ?></h5>
        <p>Here you can view and modify users of this resource as you wish but don't abuse your authority.</p>
        <form id="live-search" action="" class="styled" method="post">
            <input type="text" class="text-input" id="filter" placeholder="search" />
        </form>
        <table class="admin-users-table">
            <tr>
                <th>UID</th>
                <th>USERNAME</th>
                <th>REALNAME</th>
                <th>EMAIL</th>
                <th>BIRTHDAY</th>
                <th>IS ADMIN?</th>
                <th>EDIT</th>
                <th>DELETE</th>
            </tr>
            <?php getAllUsers($connection); ?>
        </table>
    </div>
</div>

<!-- User edit modal window -->
<div class="modal fade" id="user-edit" tabindex="-1" aria-labelledby="user-edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User: <b class="display-username"></b></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="text" disabled class="edit-uid form-control edit-window-element" value="">
                    <input type="text" class="edit-username form-control edit-window-element" value="">
                    <input type="text" class="edit-realname form-control edit-window-element" value="">
                    <input type="email" class="edit-email form-control edit-window-element" value="">
                    <input type="date" class="edit-date form-control edit-window-element" value="">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <div class="btn-group" role="group" aria-label="Ranking">
                        <input type="hidden" class="isAdminEdit" value="">
                        <button type="button" class="btn btn-outline-dark" value="" onclick='demote(this.parentNode)'>Demote</button>
                        <button type="button" class="btn btn-outline-dark" value="" onclick='promote(this.parentNode)'>Promote</button>
                    </div>
                <button type="button" class="btn btn-primary" value="" onclick='editUser(this.parentNode.parentNode)'>Save changes</button>
            </div>
        </div>
    </div>
</div>


<script src="javascript/admin.js"></script>
