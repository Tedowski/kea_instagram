<?php

require_once __DIR__.'/connect.php';
session_start();
$iUserId = $_SESSION['sUserId'];

// **********************************************************************************************************

$stmt = $db->prepare('CALL getLikedPostsByUser(:iUserId)');
$stmt->bindParam(':iUserId',$iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jRows = $stmt->fetchAll();


sendTargetObject($jRows);


// **********************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}

function sendTargetObject( $jTargetData) {
    $jFinalObject = new stdClass();
    $jFinalObject->status = 1;
    $jFinalObject->data = $jTargetData;
    echo json_encode($jFinalObject);
    exit;
}