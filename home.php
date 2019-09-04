<?php

session_start();
$iUserId = $_SESSION['sUserId'];
$sUserName = $_SESSION['sUserName'];

if( !isset($_SESSION['sUserId']) ) {
    header('Location: index.php');
}

$sInjectUserName = $sUserName;
$sInjectPageStyle = '<link rel="stylesheet" href="css/main.css">';

require_once __DIR__.'/apis/connect.php';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getProfileInfo(:iUserId)');
$stmt->bindParam('iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jUser = $stmt->fetch();

// ****************************************************************************************************

require_once __DIR__.'/top.php';
require_once __DIR__.'/header.php';
?>

<div id="home" class="page">
    <div id="feed" class="wrapper page__wrapper">
        <div class="feed_main">
            <?php

            try{
                $stmt = $db->prepare('CALL getFeedTest(:iUserId, :offset)');
                $stmt->bindParam(':iUserId', $iUserId);
                $stmt->bindValue(':offset', 0);
                $stmt->execute();

                $aPosts = $stmt->fetchAll();

                foreach ($aPosts as $jPost) {

                    if($jPost->is_liked == 1) {
                        $styleClass = ' icon-true';
                    } else if($jPost->is_liked == 0) {
                        $styleClass = '';
                    }

                    if($jPost->num_of_likes == 1) {
                        $likersText = 'person likes';
                    } else {
                        $likersText = 'people like';
                    }

                    echo '
                    <div class="item item_main">
                        <div class="item_main__heading flex__row border-bot pd_x__16 pd_y__16">
                            <div class="img-round wh-34 mg_r__16">
                                <img src="./images/'.$jPost->profile_img.'" alt="Profile">
                            </div>
                            <a class="link_name flex_item__to_left pointer" href="profile.php?iUserId='.$jPost->user_id.'">'.$jPost->username.'</a>
                            <p class="pointer">&hellip;</p>
                        </div>
                        <div class="item_main__content border-bot">
                            <img class="content__img" src="./images/'.$jPost->url.'" alt="'.$jPost->title.'">
                        </div>
                        <div class="item_main__footer" data-id="'.$jPost->id.'">
                            <div class="item__bar flex__row pd_x__16 pd_y__16">
                                <span class="fac_switch like pointer '.$styleClass.'" onclick="setLike(this)">
                                    <i class="far fa-heart"></i>
                                    <i class="fas fa-heart"></i>
                                </span>
                                <a href="post.php?postId='.$jPost->id.'" class="flex_item__to_left mg_l__16 fac_switch__hover like pointer">
                                    <i class="far fa-comment pointer"></i>
                                    <i class="fas fa-comment pointer"></i>
                                </a>
                                <span><i class="far fa-bookmark pointer"></i></span>
                            </div>
                            <div onclick="getUsersLiked('.$jPost->id.')" class="item__info pd_x__16 mg_b__8 pointer"><span class="likes_total font__bold">'.($jPost->num_of_likes).'</span> '.$likersText.' this post</div>
                            <div class="item__comments pd_x__16 pd_y_bot__16 border-bot">
                                <div class="strong inline-block">'.$jPost->username.'</div>
                                <div class="inline-block mg_l__8">'.$jPost->title.'</div>
                            </div>
                            <div class="item__comments pd_x__16 pd_y__16">
                                <a href="post.php?postId='.$jPost->id.'" class="show-comments pointer">Show <span class="comments_total">'.$jPost->num_of_comments.'</span> comments</a>
                            </div>
                        </div>
                    </div>
            ';
                }
            }catch(PDOException $ex) {
                echo $ex;
                exit;
            }

            ?>
        </div>
        <div class="feed_aside">
            <div class="sticky">
                <div class="flex__start">
                    <div class="img-round wh-50 mg_r__16">
                        <img src="./images/<?= $jUser->profile_img ?>" alt="Profile">
                    </div>
                    <div>
                        <h2 class="home_name"><?= $jUser->username ?></h2>
                        <p class="font__grey font__small mg_t__4"><?= $jUser->full_name ?></p>
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
$sScript = '<script src="js/main.js"></script>';
require_once __DIR__.'/bottom.php';


