<?php

ini_set('display_errors', 0);

session_start();
$iUserId = $_GET['iUserId'] ?? $_SESSION['sUserId'];

require_once __DIR__.'/connect.php';


// ****************************************************************************************************

$stmt = $db->prepare('CALL getProfileInfo(:iUserId)');
$stmt->bindParam('iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jUser = new stdClass();
$jUser->profile = $stmt->fetch();

$stmt = $db->prepare('CALL getPostCount(:iUserId)');
$stmt->bindParam(':iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jUser->postCount = $stmt->fetch();

$stmt = $db->prepare('CALL getFollowersInfo(:iUserId)');
$stmt->bindParam(':iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jUser->followInfo = $stmt->fetch();

sendTargetObject($jUser);

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