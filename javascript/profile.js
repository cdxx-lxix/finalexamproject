function changePassword() {
    window.history.replaceState(null, null, window.location.pathname); // Clears URL
    let oldpass = document.querySelector("#floatingPassword1").value;
    let newpass1 = document.querySelector("#floatingPassword2").value;
    let newpass2 = document.querySelector("#floatingPassword3").value;

    if (newpass1 !== newpass2) {
        location.search += "?error=drum"

    } else if (oldpass === newpass1) {
        location.search += "?error=same"
    } else {
        if (confirm("Are you sure you want to change password?")) {
            $.ajax({
                type:'POST',
                url:'processors/changeProcessor.php',
                data: { password_old: oldpass, password_new1: newpass1, viaForm: true },
                success: function(data)
                {
                    location.search += `?error=${data}`;
                },
                error: function(xhr, textStatus, error, data){
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                    location.search += `?error=${data}`;
                }
            })
        } else {
            console.log("canceled")
        }
    }
}

function editInfo() {
    window.history.replaceState(null, null, window.location.pathname); // Clears URL
    let username = document.querySelector("#Username").value;
    let realname = document.querySelector("#Realname").value;
    let email = document.querySelector("#Email").value;
    let birthday = document.querySelector("#Date").value;

    if (confirm("Are you sure you want to change your profile info?")) {
        $.ajax({
            type: 'POST',
            url: 'processors/changeProcessor.php',
            data: {viaProfile: true, username: username, realname: realname, email: email, birthday: birthday},
            success: function (data) {
                location.search += `?error=${data}`;

            },
            error: function (xhr, textStatus, error, data) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
                location.search += `?error=${data}`;
            }
        })
    } else {
        console.log("canceled")
    }
}

function deletePost(button) {
    let postID = button.value;
    if (confirm("Are you sure you want to delete this post?")) {
        $.ajax({
            type: 'POST',
            url: 'processors/postProcessor.php',
            data: {userDelete: true, post_id: postID},
            success: function (data) {
                location.search += `?error=${data}`;

            },
            error: function (xhr, textStatus, error, data) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
                location.search += `?error=${data}`;
            }
        })
    } else {
        console.log("canceled")
    }
}

function preEdit(element, post_id) {
    // Getting values to place into inputs in modal window
    let title = element.querySelector(".card-title").innerHTML;
    let content = element.querySelector(".card-text").innerHTML;
    let image = element.querySelector(".img-edit").value;

    document.querySelector(".edit-title").value = title;
    document.querySelector(".display-title").innerHTML = title;
    document.querySelector(".edit-content").innerHTML = content;
    document.querySelector(".edit-display").src = `images/${image}`;
    document.querySelector(".edit-id").value = post_id;
}

function editPost(post, post_id) {
    window.history.replaceState(null, null, window.location.pathname); // Clears URL
    let new_title = document.querySelector(".edit-title").value;
    let new_content = document.querySelector(".edit-content").value;
    let new_image = document.querySelector(".edit-image").files[0];
    // FormData because of the image
    let the_post = new FormData();
    the_post.append('userEdit', 'true');
    the_post.append('post_id', post_id);
    the_post.append('post_title', new_title);
    the_post.append('post_content', new_content);
    the_post.append('post_image', new_image);
    $.ajax({
        type: 'POST',
        url: 'processors/postProcessor.php',
        data: the_post,
        processData: false,
        contentType: false,
        success: function (data) {
            location.search += `?error=${data}`;
        },
        error: function (xhr, textStatus, error, data) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
            location.search += `?error=${data}`;
        }
    })
}