<?php

session_start();
$iUserId = $_GET['iUserId'] ?? $_SESSION['sUserId'];
$sUserName = $_SESSION['sUserName'];

if( !isset($_SESSION['sUserId']) ) {
    header('Location: index.php');
}

require_once __DIR__.'/apis/connect.php';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getProfileInfo(:iUserId)');
$stmt->bindParam('iUserId', $iUserId);
$stmt->execute();

$jUser = $stmt->fetch();

$sInjectUserName = $sUserName;
$sInjectPageStyle = '<link rel="stylesheet" href="css/main.css">';

require_once __DIR__.'/top.php';
require_once __DIR__.'/header.php';
?>
<div class="page">
    <div class="wrapper page__wrapper">
        <div class="item grid grid__1_2">
            <div class="border-right">
                <div class="pd_x__16 pd_y__16 border-bot grid grid__2_10">
                    <div class="img-round wh-34">
                        <img src="./images/<?= $jUser->profile_img ?>" alt="Profile">
                    </div>
                    <a class="link_name pointer" href="profile.php"><?= $jUser->username ?></a>
                </div>
                <form class="flex__col" action="apis/api-upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
                    <input class="pd_y__16 pd_x__16 border-bot flex_grow" type="text" name="txtTitle" id="txtTitle" placeholder="Write a caption..">
                    <input type="file" class="inputfile" name="fileToUpload" id="fileToUpload">
                    <label class="pd_y__16 pd_x__16" for="fileToUpload">Choose a file..</label>
                    <input type="submit" class="btn btn_main" value="Post">
                </form>
            </div>
            <div class="aspect">
                <div class="aspect_content__1_1">
                    <div class="aspect_content__inner flex__center">
                        <div id="uploadImg" class="img">
                            <i class="img_bg__icon far fa-image"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$sScript = '<script src="js/upload.js"></script>';
require_once __DIR__.'/bottom.php';

