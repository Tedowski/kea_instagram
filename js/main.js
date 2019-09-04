// Display feed
// **************************************************************************

$(document).ready(function() {
    // console.log('Ready');
    getFeed();

    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            // ajax call get data from server and append to the div
            getFeed();
        }
    });
})


// Pagination
// **************************************************************************

let pageOffset = 0;

function getFeed() {
    $.ajax({
        method: "GET",
        url: "apis/api-get-feed.php",
        data: {
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

// Get and display feed
// **************************************************************************

function displayFeed(data) {
    let template = $('#feedItemTemplate').contents();
    let parent = $('#feed');

    if ($(parent).html().length > 0) {
        $(parent).html('');
    }

    data.forEach(function(post) {
        let clone = $(template).clone(true);

        let postUsername = $(clone).find('#postUsername');
        let postImg = $(clone).find('#postImg');
        let postNumLikes = $(clone).find('#postNumLikes');
        let postNumComs = $(clone).find('#postNumComs');
        let postId = $(clone).find('#postId');

        $(postId).text(post.post_id);
        $(postUsername).text(post.followed_username);
        $(postImg).attr('src', `./images/${post.post_url}`);
        $(postNumLikes).text(0);
        $(postNumComs).text(0);

        $(parent).append(clone);
    });
}