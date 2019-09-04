<?php

// errors check and database connection check

ini_set('display_errors', 0);

require_once __DIR__.'/connect.php';

// Input validation
// ****************************************************************************************************

$sEmail = $_POST['txtLoginEmail'] ?? '';
if( empty($sEmail) ) {sendResponse(0, __LINE__, 'E-mail missing');}
//if( strlen($sEmail) < 2 ) {sendResponse(0, __LINE__);}
if( strlen($sEmail) > 50 ) {sendResponse(0, __LINE__, 'E-mail must be bellow 50 characters');}
if (!filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {sendResponse(0, __LINE__, 'invalid E-mail');}

$sPassword = $_POST['txtLoginPassword'] ?? '';
if( empty($sPassword) ) {sendResponse(0, __LINE__, 'Password Missing');}
if( strlen($sPassword) < 6 ) {sendResponse(0, __LINE__, 'Password must be atleast 6 characters');}
if( strlen($sPassword) > 20 ) {sendResponse(0, __LINE__, 'Password must be bellow 20 characters');}

// The BRAIN
// ****************************************************************************************************

$stmt = $db->prepare('CALL getCheckInfoForLogin()');

if(!$stmt->execute()) {
    sendResponse(0,__LINE__, "Cannot process your request");
}

$aRows = $stmt->fetchAll();

// echo json_encode($aRows);

foreach ($aRows as $aRow) {
    if($aRow->email == $sEmail) {
        if(password_verify($sPassword, $aRow->password)) {
            session_start();
            $_SESSION['sUserId'] = $aRow->id;
            $_SESSION['sUserName'] = $aRow->full_name;
            sendResponse(1,__LINE__,"Log-in successful");
        } else {
            sendResponse(0,__LINE__, "Password invalid");
        }
    }
}

sendResponse(0, __LINE__, "E-mail not registered");



// ****************************************************************************************************

function sendResponse( $iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}