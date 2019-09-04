$(document).ready(function() {
    getFollowButton();

    let query = window.location.href;
    let url = new URL(query);
    let user_id = url.searchParams.get("iUserId");

    if( user_id == null ) {
        user_id = '';
    }

    getPostsByUser(user_id);

    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            // ajax call get data from server and append to the div
            getPostsByUser(user_id);
        }
    });
});

// Pagination
// **************************************************************************

let pageOffset = 0;

function getPostsByUser(id) {
    // console.log(id);

    $.ajax({
        method: "GET",
        url: "apis/api-get-posts-by-user-full.php",
        data: {
            iUserId: id,
            iOffset: pageOffset
        },
        dataType: "JSON",
        cache: false
    }).done(function(jData) {
        if(jData.status === 1) {
            // console.log(pageOffset);

            if (jData.data === undefined || jData.data.length === 0) {
                // array empty or does not exist
                swal({
                    title: "You are up to date",
                    text: "There are no more posts",
                    icon: "success",
                });
            } else {
                console.log(jData.data);
                pageOffset++
            }

        } else {
            swal({
                title: "System update",
                text: jData.message,
                icon: "error",
            });
        }
    }).fail(function() {
        swal({
            title: "System update",
            text: "Unable to connect to the server, please try again later",
            icon: "error",
        });

    })
}

// Display profile func
// **************************************************************************

function getProfileInfo() {
    $.ajax({
        method: "GET",
        url: "apis/api-get-profile.php",
        data: {},
        dataType: "JSON",
        cache: false
    }).done(function (jData) {
        let data = jData.data;
        // console.log(data);
        displayProfile(data);
    }).fail(function () {
        console.log("Ajax error");
    })
}

function displayProfile(data) {
    let profileUsername = $('#profileUsername');
    let numOfPosts = $('#numOfPosts');
    let numOfFollowers = $('#numOfFollowers');
    let numOfFollowings = $ ('#numOfFollowings');
    let profileFullName = $('#profileFullName');
    let profileBio = $('#profileBio');

    $(profileUsername).text(data.profile.username);
    $(numOfPosts).text(data.postCount.post_count);
    $(numOfFollowers).text(data.followInfo.followers)
    $(numOfFollowings).text(data.followInfo.following);
    $(profileFullName).text(data.profile.full_name);
    $(profileBio).text(data.profile.bio);
}

// Display followers list
// **************************************************************************

function getListFollowers(id) {

    $.ajax({
        method: "GET",
        url: "apis/api-get-list-followers.php",
        data: {
            user_id: id
        },
        dataType: "JSON",
        cache: false
    }).done(function (jData) {
        let data = jData.data;
        // console.log(data);
        displayUsersList(data, "Followers");
    }).fail(function () {
        console.log("Ajax error");
    })
}

function displayFollowers(data) {
    console.log(data);
}

// Display followings list
// **************************************************************************

function getListFollowings(id) {

    $.ajax({
        method: "GET",
        url: "apis/api-get-list-followings.php",
        data: {
            user_id: id
        },
        dataType: "JSON",
        cache: false
    }).done(function (jData) {
        let data = jData.data;
        // console.log(data);
        displayUsersList(data, "Followed users");
    }).fail(function () {
        console.log("Ajax error");
    })
}

function displayFollowings(data) {
    console.log(data);
}

// Display user's posts
// **************************************************************************

function displayPostsByUser(data) {
    // console.log(data);

    let template = $('#profilePostTemplate').contents();
    let parent = $('#profilePosts');

    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    data.forEach(function(post) {

        let clone = $(template).clone(true);

        let profilePostId = $(clone).find('#profilePostId');
        let profilePostImg = $(clone).find('#profilePostImg');
        let postLike = $(clone).find('#postLike');
        let postComment = $(clone).find('#postComment');

        $(profilePostId).text(post.id);
        $(profilePostImg).attr('src', `./images/${post.url}`);
        $(postLike).text(0);
        $(postComment).text(0);

        $(parent).append(clone);
    });
}

// Set Follow
// **************************************************************************

function followUser(e) {
    let userId = $(e).data('user-id');
    console.log(userId);

    $.ajax({
        method: "GET",
        url: "apis/api-follow-user.php",
        data:{
            "iFollowedUserId": userId
        },
        dataType: "JSON",
        cache: false
    }).
    done(function (jData) {
        if(jData.status == 1){
            console.log(jData.message);
            location.reload();
        }

    }).
    fail(function () {
        console.log('error');
    });
}

// Get text value for follow button
// **************************************************************************

function getFollowButton() {
    let url_string = window.location.href;
    let url = new URL(url_string);
    let iUserId = url.searchParams.get("iUserId");

    // console.log(iUserId);

    if(iUserId == null) {
        $('.follow-user-btn').hide();
        return
    }

    $.ajax({
        method: "GET",
        url: "apis/api-get-follow-button.php",
        data:{
            "iFollowedUserId":iUserId
        },
        dataType: "JSON",
        cache: false
    }).
    done(function (jData) {
        if(jData.status == 1){
            // console.log(jData.message);
            $('.follow-user-btn').text(jData.message);
        } else if(jData.status == 0){
            // console.log(jData.message);
            $('.follow-user-btn').text(jData.message);
        }

    }).
    fail(function () {
        console.log('error');
    });}