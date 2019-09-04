$(document).ready(function() {

    let query = window.location.href;
    let url = new URL(query);
    let postId = url.searchParams.get("postId");

    // console.log(postId);
    getListComments(postId);

    setTimeout(function() {
        // console.log(postId);
        getListComments(postId);
    }, 50);
});

// Getter functions
// **************************************************************************

function getListComments(id) {
    let postId = id;
    // console.log(postId);

    $.ajax({
        method: "GET",
        url: "apis/api-get-list-comments.php",
        data: {
            iPostId: postId
        },
        dataType: "JSON",
        cache: false
    }).done(function (jData) {
        let data = jData.data;
        // console.log(data);

        displayComments(data);
    }).fail(function () {
        console.log('ajax problem');
    });
}

function getListCommentLikes(e) {
    let commentId = $(e).data('id');
    console.log(commentId);

    $.ajax({
        method: "GET",
        url: "apis/api-get-list-comment-likes.php",
        data: {
            comment_id: commentId
        },
        dataType: "JSON",
        cache: false
    }).done(function(jData) {
        let data = jData.data;
        // console.log(data);
        displayUsersList(data, "Users who liked this comment");
    }).fail(function () {
        console.log('ajax problem');
    });
}

// Display functions
// **************************************************************************

function displayComments(aData) {
    let now = $.now();

    let parent = $('#comments');
    let template = $('#commentTemplate').contents();

    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    aData.forEach(function (comment) {
        // console.log(comment);

        let clone = $(template).clone(true);
        let img = $(template).find('.img-round img');
        let content = $(template).find('.comment_content');

        $(img).attr('src', `./images/${comment.profile_img}`);

        $(content).find('a.username').text(comment.username);
        $(content).find('a.username').attr('href', `profile.php?iUserId=${comment.user_id}`);
        $(content).find('.content').text(comment.content);
        $(content).find('.likes_total').text(comment.num_of_likes);
        $(content).find('.like').attr('data-id', comment.id);
        $(content).find('.likes_total').closest('.pointer').attr('data-id', comment.id);

        if(comment.is_liked == 1) {
            $(content).find('.like').addClass('icon-true');
        } else {
            $(content).find('.like').removeClass('icon-true');
        }

        let timestamp = comment.timestamp * 1000;

        $(content).find('.timestamp').text(timeDifference(now, timestamp));

        $(parent).append(clone);
    })
}

$('.options [data-do-delete]').on('click', function(e) {
    let target = e.target;
    let postId = $(target).data('do-delete');

    $.ajax({
        method: "GET",
        url: "apis/api-delete-post.php",
        data: {
            postId: postId
        },
        dataType: "JSON",
        cache: false
    }).done(function (jData) {
        if(jData.status == 1) {
            window.history.back();

        } else {
            console.log("error");
        }
    }).fail(function () {
        console.log('AJAX error');
    })
});

$('#commentForm').on('submit', function() {
    let content = $(this).find('input').val();
    let postId = $(this).data('post-id');

    $.ajax({
        method: "GET",
        url: "apis/api-insert-comment.php",
        data: {
            postId: postId,
            text: content
        },
        dataType: "JSON",
        cache: false
    }).done(function(jData) {
        if(jData.status === 1) {
            getListComments(postId);
            location.reload();
        } else {
            console.log(jData);
        }
    }).fail(function() {
        console.log('AJAX error');
    });

    $(this).find('input').val('');

    return false
});