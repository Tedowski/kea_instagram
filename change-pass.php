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

$jUser = $stmt->fetch();

// ****************************************************************************************************

require_once __DIR__.'/top.php';
require_once __DIR__.'/header.php';
?>

<div class="page utility flex__center">
    <h2>Change password</h2>
    <div class="item card">
        <form class="form__sec" id="passForm">
            <div class="form__item">
                <label for="oldPassword">Old password</label>
                <input type="password" id="oldPassword" name="oldPassword" placeholder="Type here..">
            </div>
            <div class="form__item">
                <label for="newPassword">New password</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Type here..">
            </div>
            <div class="form__item">
                <label for="newPasswordRetyped">Confirm new password</label>
                <input type="password" id="newPasswordRetyped" name="newPasswordRetyped" placeholder="Type here..">
            </div>
            <button class="btn btn_main">Change password</button>
        </form>
    </div>
</div>


<?php
$sScript = '<script src="js/change-pass.js"></script>';
require_once __DIR__.'/bottom.php';

// ****************************************************************************************************

function sendResponse( $iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

?>