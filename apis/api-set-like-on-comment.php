<?php

ini_set('display_errors', 0);

session_start();

require_once __DIR__ . '/connect.php';

$iUserId = $_SESSION['sUserId'];

$commentId = $_GET['commentId'];
if(!ctype_digit($commentId)) {sendResponse(0, __LINE__, 'Invalid data');}

$iTimestamp = time();

// ****************************************************************************************************

try{

    $db->beginTransaction();

    $stmt = $db->prepare('CALL setLikeOnComment(:commentId, :iUserId, :timestamp)');
    $stmt->bindParam(':commentId', $commentId);
    $stmt->bindParam(':iUserId', $iUserId);
    $stmt->bindParam(':timestamp', $iTimestamp);

    if (!$stmt->execute()) {
        $db->rollBack();
        sendResponse(0,__LINE__, "Could not set like");
    }

    $stmt = $db->prepare('CALL deletePostLike()');

    if (!$stmt->execute()) {
        $db->rollBack();
        sendResponse(0,__LINE__, "Could not update table");
    }

    $db->commit();

    sendResponse(1,__LINE__, "like updated");


}catch(PDOException $ex) {
    echo $ex->getMessage();
    exit;
}


// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

function sendTargetObject($jTargetData) {
    $jFinalObject = new stdClass();
    $jFinalObject->status = 1;
    $jFinalObject->data = $jTargetData;
    echo json_encode($jFinalObject);
    exit;
}