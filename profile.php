<?php

session_start();
$iUserId = $_GET['iUserId'] ?? $_SESSION['sUserId'];
$sUserName = $_SESSION['sUserName'];

if( !isset($_SESSION['sUserId']) ) {
    header('Location: index.php');
}

require_once __DIR__.'/apis/connect.php';

$sInjectUserName = $sUserName;
$sInjectPageStyle = '<link rel="stylesheet" href="css/main.css">';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getProfileInfo(:iUserId)');
$stmt->bindParam('iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jUser = new stdClass();
$jUser->profile = $stmt->fetch();

$stmt = $db->prepare('CALL getPostCount(:iUserId)');
$stmt->bindParam(':iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jUser->postCount = $stmt->fetch();

$stmt = $db->prepare('CALL getFollowersInfo(:iUserId)');
$stmt->bindParam(':iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jUser->followInfo = $stmt->fetch();

require_once __DIR__.'/top.php';
require_once __DIR__.'/header.php';
?>

<div id="profile" class="page">
    <div class="wrapper page__wrapper">
        <div class="grid grid_bio">
            <div class="profile_main">
                <div class="img-round wh-150">
                    <img src="./images/<?= $jUser->profile->profile_img ?>" alt="<?= $jUser->profile->username ?>">
                </div>
            </div>
            <div class="profile_content">
                <div class="profile_content__main pd_y__16">
                    <h3 id="profileUsername"><?= $jUser->profile->username ?></h3>
                    <?php

                    if($iUserId == $_SESSION['sUserId']) {
                        echo '<div class="mg_l__24 size">
                                <i class="fas fa-cog pop_menu__btn"></i>
                                <ul class="list hidden">
                                    <li class="pop_menu__item"><a href="edit-info.php">Edit profile</a></li>
                                    <li class="pop_menu__item"><a href="change-pass.php">Change password</a></li>
                                    <li class="pop_menu__item"><a href="logout.php">Log out</a></li>
                                </ul>
                              </div>';
                    } else {
                        echo '<button onclick="followUser(this)" class="follow-user-btn mg_x__16" data-user-id="'.$iUserId.'"></button>';
                    }
                    ?>
                </div>
                <div class="profile_content__sec pd_y__16">
                    <h5 class="mg_r__40">Posts (<span class="strong" id="numOfPosts"><?= $jUser->postCount->post_count ?></span>)</h5>
                    <h5 onclick="getListFollowers(<?= $iUserId ?>)" class="mg_r__40 pointer">Followers (<span class="strong" id="numOfFollowers"><?= $jUser->followInfo->followers ?></span>)</h5>
                    <h5 onclick="getListFollowings(<?= $iUserId ?>)" class="pointer">Following (<span class="strong" id="numOfFollowings"><?= $jUser->followInfo->following ?></span>)</h5>
                </div>
                <div class="profile_content__bio pd_y__16">
                    <h6 id="profileFullName"><?= $jUser->profile->full_name ?></h6>
                    <p id="profileBio" class="mg_t__10"><?= $jUser->profile->bio ?></p>
                </div>
            </div>
        </div>
        <div class="profile_posts">
            <div class="grid grid_3" id="profilePosts">
                <?php

                try{
                    $stmt = $db->prepare('CALL getProfilePostsTest(:user_id, :offset, :session_id)');
                    $stmt->bindParam('user_id', $iUserId);
                    $stmt->bindParam('session_id', $_SESSION['sUserId']);
                    $stmt->bindValue('offset', 0);
                    $stmt->execute();

                    $aPosts = $stmt->fetchAll();

                    foreach ($aPosts as $jPost) {

                        if($jPost->is_liked == 1) {
                            $styleClass = ' icon-true';
                        } else if($jPost->is_liked == 0 || $jPost->is_liked == null) {
                            $styleClass = '';
                        }

                        echo '
                            <div class="post_aspect post aspect">
                                <div class="aspect_content__1_1">
                                    <div class="aspect_content__inner flex__center">
                                        <div class="img">
                                            <img src="./images/'.$jPost->url.'" alt="'.$jPost->title.'">                                    
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="post_back" data-id="'.$jPost->post_id.'">
                                    <div class="post_back__item mg_r__16">
                                        <span class="likes_total">'.$jPost->num_of_likes.'</span>
                                        <span class="fac_switch like pointer '.$styleClass.'" onclick="setLike(this)">
                                            <i class="far fa-heart"></i>
                                            <i class="fas fa-heart"></i>
                                        </span>
                                    </div>
                                    <div class="post_back__item">
                                        <span class="comments_total">'.$jPost->num_of_comments.'</span>
                                        <a href="post.php?postId='.$jPost->post_id.'" class="fac_switch__hover like pointer">
                                            <i class="far fa-comment pointer"></i>
                                            <i class="fas fa-comment pointer"></i>
                                        </a>
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
$sScript = '<script src="js/profile.js"></script>';
require_once __DIR__.'/bottom.php';

?>