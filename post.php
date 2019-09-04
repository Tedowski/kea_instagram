<?php

session_start();
$iUserId = $_SESSION['sUserId'];
$sUserName = $_SESSION['sUserName'];

$iPostId = $_GET['postId'];
if(!ctype_digit($iPostId)) {sendResponse(0, __LINE__, 'Invalid data');}


if( !isset($_SESSION['sUserId']) ) {
    header('Location: index.php');
}

require_once __DIR__.'/apis/connect.php';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getSinglePost(:user_id, :post_id)');
$stmt->bindParam(':user_id', $iUserId);
$stmt->bindParam(':post_id', $iPostId);
$stmt->execute();

$aPost = $stmt->fetch();

if($aPost->is_liked == 1) {
    $styleClass = ' icon-true';
} else if($aPost->is_liked == 0) {
    $styleClass = '';
}

if($aPost->num_of_likes == 1) {
    $likersText = 'person likes';
} else {
    $likersText = 'people like';
}

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

$sInjectUserName = $sUserName;
$sInjectPageStyle = '<link rel="stylesheet" href="css/main.css">';
require_once __DIR__.'/top.php';
require_once __DIR__.'/header.php';
?>

<div id="post" class="page">
    <div class="wrapper page__wrapper">

        <div class="item post_inner__post grid grid__2_1">
            <div class="post_inner__main aspect">
                <div class="aspect_content__1_1">
                    <div class="aspect_content__inner flex__center">
                        <div class="img">
                            <img class="" src="./images/<?= $aPost->url ?>" alt="<?= $aPost->title ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="post_inner__aside flex__col border-left">
                <div class="pd_x__16 pd_y__16 border-bot grid grid__2_10">
                    <div class="img-round wh-34">
                        <img src="./images/<?= $aPost->profile_img ?>" alt="Profile">
                    </div>
                    <a class="link_name pointer" href="profile.php?iUserId=<?= $aPost->user_id ?>"><?= $aPost->username ?></a>
                    <?php

                        if($aPost->user_id == $iUserId) {
                            echo '<div class="options size">
                                      <i class="fas fa-ellipsis-h pop_menu__btn pointer"></i>
                                      <ul class="list hidden">
                                        <li class="pop_menu__item text_red pointer" data-do-delete="'.$aPost->post_id.'">Delete post</li>
                                      </ul>
                                  </div>';
                        }

                    ?>
                </div>
                <div id="comments" class="pd_x__16 pd_y__16 border-bot flex_grow overflow_scroll"></div>
                <template id="commentTemplate">
                    <div class="grid grid__2_10 pd_y__12 comment">
                        <div class="img-round wh-34">
                            <img src="" alt="Profile">
                        </div>
                        <div class="comment_content">
                            <div class="pd_y_bot__8">
                                <a class="strong username" href="">Username:</a>
                                <span class="content"></span>
                            </div>
                            <div class="font__grey font__bold font__small">
                                <span class="timestamp"></span>
                                <span onclick="getListCommentLikes(this)" class="pointer"><span class="likes_total"></span> Likes</span>
                                <span class="fac_switch like pointer" onclick="setLikeOnComment(this)">
                                    <i class="far fa-heart"></i>
                                    <i class="fas fa-heart"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="" data-id="<?= $aPost->post_id ?>">
                    <div class="item__bar flex__row pd_x__16 pd_y__16">
                        <span class="fac_switch like pointer <?= $styleClass ?>" onclick="setLike(this)">
                            <i class="far fa-heart"></i>
                            <i class="fas fa-heart"></i>
                        </span>
                        <span class="flex_item__to_left mg_l__16 fac_switch__hover like pointer">
                            <i class="far fa-comment pointer"></i>
                            <i class="fas fa-comment pointer"></i>
                        </span>
                        <span><i class="far fa-bookmark pointer"></i></span>
                    </div>
                    <div class="item__comments pd_x__16 pd_y_bot__16">
                        <div class="strong inline-block"><?= $aPost->username ?>:</div>
                        <div class="inline-block pd_x__16"><?= $aPost->title ?></div>
                    </div>
                    <div onclick="getUsersLiked(<?= $aPost->post_id ?>)" class="item__info pd_x__16 pointer"><span class="likes_total font__bold"><?= $aPost->num_of_likes ?></span> <?= $likersText ?> this post</div>
                    <div class="pd_x__16 pd_y__16">
                        <form id="commentForm" data-post-id="<?= $aPost->post_id ?>">
                            <input type="text" name="commentTxt" id="commentTxt" placeholder="Write comment..">
                            <button class="hidden">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="modal">
        <div class="modal__content">
            <div class="border-bot flex__center pd_x__16 pd_y__16 title">
                <h5>Title</h5>
            </div>
            <div id="usersList"></div>
        </div>
    </div>
    <template id="itemUserTemplate">
        <a class="flex__start pd_y__8 a-search">
            <div class="img-round wh-45 mg_l__16 mg_r__8">
                <img class="search_img" src="" alt="Profile">
            </div>
            <h5 class="username"></h5>
        </a>
    </template>

<?php
$sScript = '<script src="js/post.js"></script>';
require_once __DIR__.'/bottom.php';
