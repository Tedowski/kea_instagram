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
    <h2>Edit info</h2>
    <div class="item card">
        <form class="form__sec" id="infoForm">
            <div class="form__item">
                <label for="newFullName">Full name</label>
                <input type="text" id="newFullName" name="newFullName" value="<?= $jUser->full_name ?>">
            </div>
            <div class="form__item">
                <label for="newBio">Your bio</label>
                <textarea id="newBio" name="newBio"><?= $jUser->bio ?></textarea>
            </div>
            <button class="btn btn_main">Save info</button>
        </form>
    </div>
</div>


<?php
$sScript = '<script src="js/edit-info.js"></script>';
require_once __DIR__.'/bottom.php';

// ****************************************************************************************************

function sendResponse( $iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

?>
