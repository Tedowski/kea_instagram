<?php

ini_set('display_errors', 0);

session_start();
$iUserId = $_SESSION['sUserId'];

$iPostId = $_GET['iPostId'];
if(!ctype_digit($iPostId)) {sendResponse(0, __LINE__, 'Invalid data');}

require_once __DIR__.'/connect.php';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getAllUsersFromLikes(:iPostId)');
$stmt->bindParam(':iPostId', $iPostId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$aRows = $stmt->fetchAll();

sendTargetObject($aRows);

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