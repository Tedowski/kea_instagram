<?php

ini_set('display_errors', 0);

session_start();

$iUserId = $_SESSION['sUserId'];

$iPostId = $_GET['post_id'];

require_once __DIR__ . '/connect.php';

$stmt = $db->prepare('CALL getLikesForPost(:user_id, :post_id)');
$stmt->bindParam(':user_id', $iUserId);
$stmt->bindParam(':post_id', $iPostId);

$stmt->execute();

$aRow = $stmt->fetch();

sendTargetObject($aRow);

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