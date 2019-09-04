<?php

ini_set('display_errors', 0);

session_start();

require_once __DIR__ . '/connect.php';

$iUserId = $_SESSION['sUserId'];

//$iCommentId = $_GET['replyId'];

$iPostId = $_GET['postId'];
if(!ctype_digit($iPostId)) {sendResponse(0, __LINE__, 'Invalid data');}

$sContent = $_GET['text'];
if( strlen($sContent) > 255 ) {sendResponse(0, __LINE__, 'Invalid content');}

$iTimestamp = time();

// ****************************************************************************************************

try{

    $stmt = $db->prepare('CALL insertComment(:postId, :sContent, :iTimestamp, :userId)');
    $stmt->bindParam(':postId', $iPostId);
    $stmt->bindParam(':sContent', $sContent);
    $stmt->bindParam(':iTimestamp', $iTimestamp);
    $stmt->bindParam(':userId', $iUserId);

    $stmt->execute();

    sendResponse(1, __LINE__, "Comment added");


}catch(PDOException $ex){
    echo $ex->getMessage();
    exit;
}

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}