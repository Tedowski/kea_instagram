
$(document).ready(function() {
    // console.log('DONE');
    // getProfileInfo();
    // getPostsByUser();
    // getEmotions();
    // getComments();
    // getLikedPostsByUser();
});

// Pager
// **************************************************************************

$('.pager').on('click', function() {
    let toOpen = $(this).data('to-open');
    $('.page').addClass('hidden');

    $(`#${toOpen}`).removeClass('hidden');
})

// Open popup menu
// **************************************************************************

$('.pop_menu__btn').on('click', function() {
    $(this).closest('.size').find('.list').toggleClass('hidden');
})

// Setters
// **************************************************************************

function setLike(e) {
    let target = e;
    let postId = $(target).closest('div[data-id]').data('id');
    // console.log(postId);
    $.ajax({
        method: "GET",
        url: "apis/api-set-like.php",
        data: {
            iPostId: postId
        },
        dataType: "JSON",
        cache: false
    }).done(function () {
        getEmotions(postId);
    }).fail(function () {
        console.log('ajax problem');
    });
}

function setLikeOnComment(e) {
    let target = e;
    let commentId = $(target).data('id');
    // console.log(commentId)

    $.ajax({
        method: "GET",
        url: "apis/api-set-like-on-comment.php",
        data: {
            commentId: commentId
        },
        dataType: "JSON",
        cache: false
    }).done(function() {
        getCommentEmotions(commentId);

    }).fail(function() {
        console.log('ajax problem');
    });
}

// Getters
// **************************************************************************


function getUsersLiked(id) {
    let postId = id;

    // console.log('clicked');
    $.ajax({
        method: "GET",
        url: "apis/api-get-list-likes.php",
        data: {
            iPostId: postId
        },
        dataType: "JSON",
        cache: false
    }).done(function(jData) {
        let data = jData.data;
        // console.log(data);
        displayUsersList(data, "Users who liked this post");
    }).fail(function () {
        console.log('ajax problem');
    });
}

function getEmotions(id) {
    $.ajax({
        method: "GET",
        url: "apis/api-get-emotions.php",
        data: {
            post_id: id
        },
        dataType: "JSON",
        cache: false
    }).done(function(jData) {
        let data = jData.data;
        // console.log(data.is_liked);

        let postId = data.id;
        let numOfLikes = data.num_of_likes;

        let div = $(`div[data-id=${postId}]`);

        $(div).find('.likes_total').text(numOfLikes);

        if(data.is_liked == 1) {
            $(div).find('.fac_switch').addClass('icon-true');
        } else if (data.is_liked == 0 || data.is_liked == null) {
            $(div).find('.fac_switch').removeClass('icon-true');
        }

    }).fail(function() {
        console.log('Likes not retrieved');
    })
}

function getCommentEmotions(id) {
    $.ajax({
        method: "GET",
        url: "apis/api-get-comment-emotions.php",
        data: {
            comment_id: id
        },
        dataType: "JSON",
        cache: false
    }).done(function(jData) {
        let data = jData.data;
        console.log(data);

        let commentId = data.id;
        let numOfLikes = data.num_of_likes;

        let div = $(`.like[data-id=${commentId}]`);

        $(div).closest('.font__small').find('.likes_total').text(numOfLikes);

        if(data.is_liked == 1) {
            $(div).addClass('icon-true');
        } else if (data.is_liked == 0 || data.is_liked == null) {
            $(div).removeClass('icon-true');
        }

    }).fail(function() {
        console.log('Likes not retrieved');
    })
}

// function getComments() {
//     $.ajax({
//         method: "GET",
//         url: "apis/api-get-comments.php",
//         data: {},
//         dataType: "JSON",
//         cache: false
//     }).done(function(jData) {
//         let data = jData.data;
//         for(let i = 0; i < data.length; i++) {
//             // console.log(data[i]);
//             let postId = data[i].id;
//             let numOfComments = data[i].comments;
//             $(`div[data-id=${postId}]`).find('.comments_total').text(numOfComments);
//         }
//     }).fail(function() {
//         console.log('Comments not retrieved');
//     })
// }


// Display functions
// **************************************************************************

function timeDifference(timestamp1, timestamp2) {
    let difference = timestamp1 - timestamp2;
    difference = Math.floor(difference/1000);

    if(difference > 604800) {
        let weeksDifference = Math.floor(difference/60/60/24/7);

        return weeksDifference + ' w';
    } else if(difference < 604800 && difference > 86400) {
        let daysDifference = Math.floor(difference/60/60/24);

        return daysDifference + ' d';
    } else if(difference < 86400 && difference > 3600) {
        let hoursDifference = Math.floor(difference/60/60);

        return hoursDifference + ' h';
    } else if(difference < 3600 && difference > 60) {
        let minutesDifference = Math.floor(difference/60);

        return minutesDifference + ' m';
    } else {
        return difference + ' s';
    }

}

function displayUsersList(aData, title) {
    openModal(title);
    let template = $('#itemUserTemplate').contents();
    let parent = $('#usersList');

    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    if (aData === undefined || aData.length === 0) {

        $(parent).html('<p class="font__bold font__grey pd_x__16 pd_y__16 flex__center">No users found</p>');

    } else {

        aData.forEach(function (link) {
            // console.log(link);
            let clone = $(template).clone(true);
            let anchor = $(clone).find('.a-search');
            let a = anchor.prevObject[1];
            let img = $(clone).find('.search_img');
            let content = $(clone).find('.username');

            $(a).attr('href', `profile.php?iUserId=${link.id}`);
            $(a).attr("data-test", link.id);
            $(content).text(link.username);
            $(img).attr('src', `./images/${link.profile_img}`);

            $(parent).append(clone);
        })
    }
}

function displayNames(data) {
    let template = $('#itemSearchTemplate').contents();
    let parent = $('.dropdown-content');

    $('.dropdown-content').css('display', 'block');

    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    data.forEach(function (link) {
        console.log(link);
        let clone = $(template).clone(true);
        let anchor = $(clone).find('.a-search');
        let a =  anchor.prevObject[1];
        let img = $(clone).find('.search_img');
        let content = $(clone).find('.username');

        $(a).attr('href', `profile.php?iUserId=${link.id}`);
        $(a).attr("data-test", link.id);
        $(content).text(link.username);
        $(img).attr('src', `./images/${link.profile_img}`);

        $(parent).append(clone);
    });
}

// function getLikedPostsByUser() {
//     $.ajax({
//         method: "GET",
//         url: "apis/api-get-liked-posts-by-user.php",
//         data: {},
//         dataType: "JSON",
//         cache: false
//     }).done(function (jData) {
//         // console.log(jData.data);
//         let data = jData.data;
//         data.forEach(function(post) {
//             // console.log(post);
//             let iPostId = post.post_id;
//             $(`[data-id=${iPostId}]`).find('.like').addClass('icon-true');
//         });
//     }).fail(function () {
//         console.log('AJAX error');
//     })
// }

// Event handlers
// **************************************************************************


$('#searchBar').on('input', function() {
    //console.log('input changed');
    if($(this).find('#txtName').val() != '') {
        // console.log($('#txtName').val());
        $.ajax({
            method: "GET",
            url: "apis/api-get-users-by-search.php",
            data: {
                "sName": $('#txtName').val()
            },
            cache: false,
            dataType: "JSON"

        }).done(function(jData) {
            if (jData.status == 0) {
                console.log(jData.message);
            } else if (jData.status == 1) {
                let data = jData.data;
                displayNames(data);
            }

        }).fail(function() {
            console.log('Ajax error');
        });
    } else {
        $('.dropdown-content').css('display', 'none');
        $('.dropdown-content').html('');
    }
});

function openModal(title) {
    $('.modal').addClass('open');
    $('.modal').find('.title h5').text(title);
    $('body').css('overflow-y', 'hidden');
}

$(window).on('click', function(e) {
    let target = e.target;
    if ($(target).hasClass('modal')) {
        $(target).removeClass('open');
        $('body').css('overflow-y', 'scroll');
    }
})