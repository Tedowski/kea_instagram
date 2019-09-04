<?php

ini_set('display_errors', 0);

session_start();

require_once __DIR__ . '/connect.php';

// ****************************************************************************************************

$stmt = $db->prepare('CALL getNumComments()');

$stmt->execute();

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