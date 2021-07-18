<script src="javascript/profile.js"></script>
<div class="leftcolumn">

    <div class="card">
        <h2>Profile</h2>
        <h5>Today is <?php echo date('d-m-Y'); ?></h5>
        <div class="profile-box">

            <div class="profile-image">
                <img src="<?php echo "images/" . $_SESSION["user_img"] ?> " alt="" class="avatar">
            </div>

            <div class="profile-info">
                <p>Here is your info:</p>
                <p>Username: <b><?php echo $_SESSION['username'] ?></b></p>
                <p>Real name: <b><?php echo $_SESSION['realname'] ?></b></p>
                <p>Email: <b><?php echo $_SESSION['email'] ?></b></p>
                <p>Your birthday: <b><?php echo $_SESSION['birthday'] ?></b></p>
                <hr>

                <div class="profile-buttons">
                    <button class="btn btn-primary btn-edit" data-toggle="modal" data-target="#password-changer">Change password</button>
                    <button class="btn btn-primary btn-edit" data-toggle="modal" data-target="#info-changer">Edit info</button>
                    <button class="btn btn-primary btn-edit" data-toggle="modal" data-target="#image-uploader">Set avatar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <h2>Your posts</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php include 'personalPosts.php'?>
        </div>
    </div>
    <?php
    require_once 'processors/dbcu.php';
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $records_per_page = 25;
    $offset = ($page - 1) * $records_per_page;
    paginationFormatter($connection, $records_per_page, 'posts', $page);
    ?>
</div>

<!-- Avatar modal window -->
<div class="modal fade" id="image-uploader" tabindex="-1" role="dialog" aria-labelledby="image-uploader" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="image-uploaderLongTitle">Select avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="drop-area">
                <form action="processors/avatarProcessor.php" method="POST" enctype="multipart/form-data">
                    <p>Select your future avatar. Maximum size - <b>5mb</b>. Maximum resolution - <b>500x500px</b>.</p>
                    <input type="file" id="fileElem" accept="image/jpeg, image/png, image/jpg" name="avatar">
                    <label class="button" for="fileElem">.png /.jpg /.jpeg</label>
                    <hr>
                    <button type="button" class="btn btn-secondary img-btn" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary img-btn" name="upload-avatar" value="Upload">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password change modal window -->
<div class="modal fade" id="password-changer" tabindex="-1" aria-labelledby="password-changer" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create new password</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword1" required placeholder="Old password" name="old-password">
                        <label for="floatingPassword1">Old password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword2" required placeholder="New password" name="new-password1">
                        <label for="floatingPassword2">New password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword3" required placeholder="New password (repeat)" name="new-password2">
                        <label for="floatingPassword3">New password (repeat)</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="changePassword()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Info change modal window -->
<div class="modal fade" id="info-changer" tabindex="-1" aria-labelledby="info-changer" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change your info</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="Username" disabled placeholder="Username" name="Username" value="<?php echo $_SESSION['username'] ?>">
                        <label for="Username">Username</label>
                        <div class="form-text">Usernames are unique and unchangeable. Deal with it or register a new account</div>
                    </div>
                    <hr>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="Realname" placeholder="Real name" name="realname" maxlength="50" value="<?php echo $_SESSION['realname'] ?>">
                        <label for="Realname">Real name</label>
                        <div class="form-text">Maybe it's not so real after all</div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="Email" placeholder="Email" name="email" maxlength="50" value="<?php echo $_SESSION['email'] ?>">
                        <label for="Email">Email</label>
                        <div class="form-text">You'll need to restore an account and receive feedback</div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="Date" placeholder="Birthday" name="birthday" value="<?php echo $_SESSION['birthday'] ?>">
                        <label for="Date">Birthday</label>
                        <div class="form-text">This place isn't a pornhub you can say truth</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editInfo()">Save changes</button>
            </div>
        </div>
    </div>
</div>

