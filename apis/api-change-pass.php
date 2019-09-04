<?php

session_start();

// errors check and database connection check

ini_set('display_errors', 0);

require_once __DIR__.'/connect.php';

$iUserId = $_SESSION['sUserId'];

// Get users current password and store it
// ****************************************************************************************************

$stmt = $db->prepare('CALL getPassword(:iUserId)');
$stmt->bindParam(':iUserId', $iUserId);

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$jRow = $stmt->fetch();

$jPassword = $jRow->password;

// Input validation
// ****************************************************************************************************

$oldPassword = $_POST['oldPassword'] ?? '';
if( empty($oldPassword) ) {sendResponse(0, __LINE__, 'Old Password Missing');}
if(!password_verify($oldPassword, $jPassword)) {sendResponse(0,__LINE__,"Invalid old password");}

$newPassword = $_POST['newPassword'] ?? '';
$newPasswordRetyped = $_POST['newPasswordRetyped'] ?? '';
if( empty($newPassword) ) {sendResponse(0, __LINE__, 'Password Missing');}
if( strlen($newPassword) < 6 ) {sendResponse(0, __LINE__, 'Password must be atleast 6 characters');}
if( strlen($newPassword) > 20 ) {sendResponse(0, __LINE__, 'Password must be bellow 20 characters');}
if( empty($newPasswordRetyped) ) {sendResponse(0, __LINE__, 'You need to confirm new password');}
if($newPassword != $newPasswordRetyped) {sendResponse(0, __LINE__, 'Passwords do not match');}

$newPasswordHashed = password_hash($newPassword, 1);

// Update password
// ****************************************************************************************************

try{

    $stmt = $db->prepare('CALL updatePassword(:newPass, :userId)');
    $stmt->bindParam(':newPass', $newPasswordHashed);
    $stmt->bindParam(':userId', $iUserId);

    if(!$stmt->execute()) {
        sendResponse(0,__LINE__, "Cannot process your request");
    }

    sendResponse(1,__LINE__,"Password changed!");


}catch(PDOException $ex) {
    echo $ex->getMessage();
    exit;
}

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}