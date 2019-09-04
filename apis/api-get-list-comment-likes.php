<?php

ini_set('display_errors', 0);

session_start();

$commentId = $_GET['comment_id'];
if(!ctype_digit($commentId)) {sendResponse(0, __LINE__, 'Invalid data');}

require_once __DIR__.'/connect.php';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getAllUsersFromCommentLikes(:comment_id)');
$stmt->bindParam(':comment_id', $commentId);

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