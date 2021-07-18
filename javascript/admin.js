/* SECTION OF ADMIN PANEL FOR USERS */

function preEditUser(element) {
    // Taking values:
    let uid = element.querySelector(".uid").innerHTML;
    let username = element.querySelector(".username").innerHTML;
    let realname = element.querySelector(".realname").innerHTML;
    let email = element.querySelector(".email").innerHTML;
    let birthday = element.querySelector(".birthday").innerHTML;
    let isAdmin = element.querySelector(".isAdmin").innerHTML;
    // Inserting values:
    document.querySelector(".edit-uid").value = uid;
    document.querySelector(".edit-username").value = username;
    document.querySelector(".edit-realname").value = realname;
    document.querySelector(".edit-email").value = email;
    document.querySelector(".edit-date").value = birthday;
    document.querySelector(".isAdminEdit").value = isAdmin;
}

function editUser(element) {
    let uid = element.querySelector(".edit-uid").value;
    let username = element.querySelector(".edit-username").value;
    let realname = element.querySelector(".edit-realname").value;
    let email = element.querySelector(".edit-email").value;
    let birthday = element.querySelector(".edit-date").value;

    $.ajax({
        type: 'POST',
        url: 'admin/userControl.php',
        data: {adminEdit: true, uid: uid, username: username, realname: realname, email: email, birthday: birthday},
        success: function () {
            window.history.replaceState(null, null, window.location.pathname);
            location.search += `?action=Users`;
        },
        error: function (xhr, textStatus, error, data) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            location.search += `?error=${data}`;
        }
    })
}

function promote(element) {
    let father = element.parentNode.parentNode;
    let uid = father.querySelector(".edit-uid").value;
    let username = father.querySelector(".edit-username").value;
    let isAdmin = element.querySelector(".isAdminEdit").value;
    if (isAdmin === "1") {
        alert("You can't promote an admin!");
    } else {
        $.ajax({
            type:'POST',
            url:'admin/userControl.php',
            data: { adminPromote: true, uid: uid, username: username},
            success: function()
            {
                alert(`${username} is now an admin!`);
                window.history.replaceState(null, null, window.location.pathname);
                location.search += `?action=Users`;
            }
        })
    }

}

function demote(element) {
    let father = element.parentNode.parentNode;
    let uid = father.querySelector(".edit-uid").value;
    let username = father.querySelector(".edit-username").value;
    let isAdmin = element.querySelector(".isAdminEdit").value;
    if (isAdmin === "1") {
        $.ajax({
            type:'POST',
            url:'admin/userControl.php',
            data: { adminDemote: true, uid: uid, username: username},
            success: function()
            {
                alert(`${username} is now a peasant!`);
                window.history.replaceState(null, null, window.location.pathname);
                location.search += `?action=Users`;
            }
        })
    } else {
        alert("You can't demote a peasant!");
    }
}

function deleteUser(button) {
    let id = button.value;
    let row = button.parentElement.parentElement;
    if (confirm("Are you sure you want to delete this user?")) {
        $.ajax({
            type:'POST',
            url:'admin/userControl.php',
            data: { delete_id: id },
            success: function(data)
            {
                // Excludes deleted row from the table without reload
                row.remove();
            }
        })
    } else {
        console.log("canceled")
    }
}

/* SECTION OF ADMIN PANEL FOR CATEGORIES */

function preEditCategory(element) {
    // Taking values:
    let cat_id = element.querySelector(".cat-id").innerHTML;
    let cat_name = element.querySelector(".cat-name").innerHTML;
    // Inserting value:
    document.querySelector(".edit-cat-id").value = cat_id;
    document.querySelector(".edit-cat-name").value = cat_name;
    document.querySelector(".edit-cat-title").innerHTML = cat_name;
}

function editCategory(element) {
    let cat_id = element.querySelector(".edit-cat-id").value;
    let cat_name = element.querySelector(".edit-cat-name").value;

    $.ajax({
        type: 'POST',
        url: 'admin/categoryControl.php',
        data: {adminEdit: true, cat_id: cat_id, cat_name: cat_name},
        success: function () {
            window.history.replaceState(null, null, window.location.pathname);
            location.search += `?action=Categories`;
        },
        error: function (xhr, textStatus, error, data) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            location.search += `&error=${data}`;
        }
    })
}

function addCategory(element) {
    let cat_name = element.querySelector(".new-cat-name").value;
    $.ajax({
        type: 'POST',
        url: 'admin/categoryControl.php',
        data: {adminAdd: true, cat_name: cat_name},
        success: function () {
            window.history.replaceState(null, null, window.location.pathname);
            location.search += `?action=Categories`;
        },
        error: function (xhr, textStatus, error, data) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            location.search += `&error=${data}`;
        }
    })
}

function deleteCategory(button) {
    let id = button.value;
    let row = button.parentElement.parentElement;
    if (confirm("Are you sure you want to delete this category?")) {
        $.ajax({
            type:'POST',
            url:'admin/categoryControl.php',
            data: { delete_id: id },
            success: function(data)
            {
                // Excludes deleted row from the table without reload
                row.remove();
            }
        })
    } else {
        console.log("canceled")
    }
}

/* SECTION OF ADMIN PANEL FOR POSTS */

function preEditPost(post_id) {
    $.ajax({
        type:'POST',
        url:'admin/postControl.php',
        data: { adminPreEdit: true, edit_id: post_id },
        success: function(data)
        {
            let parsedData = JSON.parse(data);
            document.querySelector(".display-title").innerHTML = parsedData['POST_TITLE'];
            document.querySelector(".post-id").value = parsedData['POST_ID'];
            document.querySelector(".post-title").value = parsedData['POST_TITLE'];
            document.querySelector(".post-category").value = parsedData['POST_CATEGORY'];
            document.querySelector(".post-date").value = parsedData['POST_DATE'];
            document.querySelector(".post-author").value = parsedData['POST_AUTHOR'];
            document.querySelector(".post-content").value = parsedData['POST_CONTENT'];
            document.querySelector(".post-display").src = `images/${parsedData['POST_IMAGE']}`;
        }
    })
}

function editPostAdmin(element) {
    let postID = element.querySelector(".post-id").value;
    let postTitle = element.querySelector(".post-title").value;
    let postCat = element.querySelector(".post-category").value;
    let postDate = element.querySelector(".post-date").value;
    let postAuthor = element.querySelector(".post-author").value;
    let postContent = element.querySelector(".post-content").value;
    let postImage = element.querySelector(".post-image").files[0];
    // Creating form data because of the image
    let the_post = new FormData();
    the_post.append('adminEdit', 'true');
    the_post.append('post_id', postID);
    the_post.append('post_title', postTitle);
    the_post.append('post_cat', postCat);
    the_post.append('post_date', postDate);
    the_post.append('post_author', postAuthor);
    the_post.append('post_content', postContent);
    the_post.append('post_image', postImage);

    $.ajax({
        type:'POST',
        url:'admin/postControl.php',
        data: the_post,
        processData: false,
        contentType: false,
        success: function(data)
        {
            alert(`Post №${data} updated!`)
            window.history.replaceState(null, null, window.location.pathname);
            location.search += `?action=Posts&page=1s`;
        }
    })
}

function deletePost(button) {
    let id = button.value;
    let row = button.parentElement.parentElement;
    if (confirm("Are you sure you want to delete this post?")) {
        $.ajax({
            type:'POST',
            url:'admin/postControl.php',
            data: { delete_id: id },
            success: function(data)
            {
                // Excludes deleted row from the table without reload
                row.remove();
            }
        })
    } else {
        console.log("canceled")
    }
}

/* LIVE SEARCH */
$("#filter").keyup(function () {
    let value = this.value.toLowerCase().trim();

    $("table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            let id = $(this).text().toLowerCase().trim();
            let not_found = (id.indexOf(value) === -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });
});