<?php

// errors check and database connection check

ini_set('display_errors', 0);

require_once __DIR__.'/connect.php';

// Input validation
// ****************************************************************************************************

$sUsername = $_POST['txtSignupUsername'] ?? '';
if( empty($sUsername) ) {sendResponse(0, __LINE__, "Username missing");}
if( !preg_match('/[a-z A-Z0-9_-]+$/', $sUsername) ) {sendResponse(0, __LINE__, 'Username can only contain letters, numbers, and scores and underscores');}
if( strlen($sUsername) < 6 ) {sendResponse(0, __LINE__, 'Username must be at least 6 characters');}
if( strlen($sUsername) > 40 ) {sendResponse(0, __LINE__, 'Username must be below 40 characters');}

$sEmail = $_POST['txtSignupEmail'] ?? '';
if( empty($sEmail) ) {sendResponse(0, __LINE__, 'E-mail missing');}
//if( strlen($sEmail) < 2 ) {sendResponse(0, __LINE__);}
if( strlen($sEmail) > 50 ) {sendResponse(0, __LINE__, 'E-mail must be bellow 50 characters');}
if (!filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {sendResponse(0, __LINE__, 'invalid E-mail');}

$sFullName = $_POST['txtSignupFullName'] ?? '';
if( empty($sFullName) ) {sendResponse(0, __LINE__, "Name missing");}
if( !preg_match('/[a-z A-Z]+$/', $sFullName) ) {sendResponse(0, __LINE__, 'Name can only contain letters and spaces');}
if( strlen($sFullName) < 2 ) {sendResponse(0, __LINE__, 'Name must be at least 6 characters');}
if( strlen($sFullName) > 100 ) {sendResponse(0, __LINE__, 'Name must be below 40 characters');}

$sPassword = $_POST['txtSignupPassword'] ?? '';
$sPasswordRetyped = $_POST['txtSignupPasswordRetyped'] ?? '';
if( empty($sPassword) ) {sendResponse(0, __LINE__, 'Password Missing');}
if( strlen($sPassword) < 6 ) {sendResponse(0, __LINE__, 'Password must be atleast 6 characters');}
if( strlen($sPassword) > 20 ) {sendResponse(0, __LINE__, 'Password must be bellow 20 characters');}
if( empty($sPasswordRetyped) ) {sendResponse(0, __LINE__, 'You need to confirm new password');}
if($sPassword != $sPasswordRetyped) {sendResponse(0, __LINE__, 'Passwords do not match');}

$sPasswordHashed = password_hash($sPassword, 1);


// The BRAIN
// ****************************************************************************************************

$stmt = $db->prepare('CALL getCheckInfoForSignup();');

if(!$stmt->execute()) {
    sendResponse(0, __LINE__, "Cannot process your request");
}


$aRows = $stmt->fetchAll();

foreach($aRows as $aRow) {
    if( $aRow->username == $sUsername ) {
        sendResponse(0, __LINE__, "Username already in use");
    }

    if( $aRow->email == $sEmail ) {
        sendResponse(0, __LINE__, "E-mail already registered");
    }
}

$stmt = $db->prepare('CALL createSingleUser(:username, :email, :full_name, :password, :profile_img, :is_public, :bio)');
$stmt = $db->prepare('SELECT * FROM');
$stmt->bindParam(':username', $sUsername);
$stmt->bindParam(':email', $sEmail);
$stmt->bindParam(':full_name', $sFullName);
$stmt->bindParam(':password', $sPasswordHashed);
$stmt->bindValue(':profile_img', 'user.png');
$stmt->bindValue(':is_public', 1);
$stmt->bindValue(':bio', 'Instagram user');

if(!$stmt->execute()) {
    sendResponse(0, __LINE__, "Cannot process your request");
}

$stmt = $db->prepare('SELECT LAST_INSERT_ID() AS user_id');

if(!$stmt->execute()) {
    sendResponse(0, __LINE__, "Cannot Get your identification");
}

$jRow = $stmt->fetch();
$returnId = $jRow->user_id;


$stmt = $db->prepare('CALL followUser(:user_id, :followed_id)');
$stmt->bindParam(':user_id', $returnId);
$stmt->bindParam(':followed_id', $returnId);

if(!$stmt->execute()) {
    sendResponse(0, __LINE__, "Cannot follow yourself");
}

sendResponse(1, __LINE__, "You have been successfully registered");

// ****************************************************************************************************

function sendResponse( $iStatus, $iLineNumber, $sMessage) {
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
}