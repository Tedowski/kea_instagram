<?php

ini_set('display_errors', 0);

session_start();
$iUserId =  $_SESSION['sUserId'];

$iOffset = $_GET['iOffset'] * 10;


require_once __DIR__.'/connect.php';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getFeedTest(:iUserId, :iOffset)');
$stmt->bindParam(':iUserId', $iUserId);
$stmt->bindParam(':iOffset', $iOffset);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$aPosts = $stmt->fetchAll();

sendTargetObject($aPosts);

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