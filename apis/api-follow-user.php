<?php

ini_set('display_errors', 0);

session_start();

require_once __DIR__ . '/connect.php';

$iActiveUserId = $_SESSION['sUserId'];

$iFollowedUserId = $_GET['iFollowedUserId'];

try{


    $db->beginTransaction();

    $stmt = $db->prepare('CALL followUser(:iActiveUserId,:iFollowedUserId)');
    $stmt->bindParam(':iActiveUserId',$iActiveUserId);
    $stmt->bindParam(':iFollowedUserId',$iFollowedUserId);

    if (!$stmt->execute()) {
        $db->rollBack();
        sendResponse(0,__LINE__, "Could not follow user.");
    }

    $stmt = $db->prepare('CALL deleteFollow()');

    if (!$stmt->execute()) {
        $db->rollBack();
        sendResponse(0,__LINE__, "Could not update table");
    }

    $db->commit();

    sendResponse(1,__LINE__, "follow updated");


}catch(PDOException $ex) {
    echo $ex->getMessage();
    exit;
}

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}
