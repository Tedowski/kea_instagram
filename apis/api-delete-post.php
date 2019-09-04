<?php

ini_set('display_errors', 0);

session_start();

require_once __DIR__ . '/connect.php';

$iUserId = $_SESSION['sUserId'];

$iPostId = $_GET['postId'];
if(!ctype_digit($iPostId)) {sendResponse(0, __LINE__, 'Invalid data');}

// ****************************************************************************************************

try{

    $stmt = $db->prepare('SELECT id, user_fk FROM posts WHERE id = :postId');
    $stmt->bindParam(':postId', $iPostId);
    $stmt->execute();

    $jPost = $stmt->fetch();

    if($jPost->user_fk != $iUserId) {
        sendResponse(0,__LINE__, "You cannot do this");
    }

    $stmt = $db->prepare('CALL deletePost(:post_id)');
    $stmt->bindParam(':post_id', $iPostId);

    if(!$stmt->execute()) {
        sendResponse(0,__LINE__,"Cannot process your request");
    }

    sendResponse(1,__LINE__,"DONE");

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