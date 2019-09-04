<?php

session_start();

// errors check and database connection check

ini_set('display_errors', 0);

require_once __DIR__.'/connect.php';

$iUserId = $_SESSION['sUserId'];

// Input validation
// ****************************************************************************************************

$newFullName = $_POST['newFullName'] ?? '';
if( empty($newFullName) ) {sendResponse(0, __LINE__, "Name missing");}
if( !preg_match('/[a-z A-Z]+$/', $newFullName) ) {sendResponse(0, __LINE__, 'Name can only contain letters and spaces');}
if( strlen($newFullName) < 2 ) {sendResponse(0, __LINE__, 'Name must be at least 6 characters');}
if( strlen($newFullName) > 100 ) {sendResponse(0, __LINE__, 'Name must be below 40 characters');}

$newBio = $_POST['newBio'] ?? '';
if( !preg_match('/[a-z A-Z0-9_-]+$/', $newBio) ) {sendResponse(0, __LINE__, 'Bio can only contain letters, numbers, and scores and underscores');}
if( strlen($newBio) > 255 ) {sendResponse(0, __LINE__, 'Bio must be below 255 characters');}

// The Brain
// ****************************************************************************************************

try{

    $stmt = $db->prepare('CALL updateInfo(:sName, :sBio, :iUserId)');
    $stmt->bindParam(':sName', $newFullName);
    $stmt->bindParam(':sBio', $newBio);
    $stmt->bindParam(':iUserId', $iUserId);

    if($stmt->execute()) {
        sendResponse(1,__LINE__,"info updated");
    }

}catch(PDOException $ex) {
    echo $ex->getMessage();
    exit;
}

// ****************************************************************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}