<?php

ini_set('display_errors', 0);

require_once _DIR_.'/../connect.php';

session_start();

$iActiveUserId = $_SESSION['sUserId'];
$iFollowedUserId = $_GET['iFollowedUserId'];


$stmt = $db->prepare('CALL getFollowButton(:iActiveUserId,:iFollowedUserId)');
$stmt->bindParam(':iActiveUserId',$iActiveUserId);
$stmt->bindParam(':iFollowedUserId',$iFollowedUserId);

$stmt->execute();


$count = $stmt->rowCount();


if ($count == 1){
    sendResponse(1,__LINE__,"Unfollow");
}
if ($count == 0){
    sendResponse(0,__LINE__,"Follow");
}



//*********************************
function sendResponse($iStatus,$iLine,$sMessage){
    echo '{"status":'.$iStatus.',"code":"'.$iLine.'","message":"'.$sMessage.'"}';
    exit;
}