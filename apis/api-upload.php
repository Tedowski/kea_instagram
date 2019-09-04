<?php

session_start();
$iUserId = $_SESSION['sUserId'];

if( !isset($_SESSION['sUserId']) ) {
    sendResponse(0,__LINE__, 'cannot use this api');
}

require_once __DIR__.'/connect.php';

//user_fk
//title
//url
//timestamp --

$sTitle = $_POST['txtTitle'];

$iTimestamp = time();

// ini_set("display_errors", 0);

$target_dir = "../images/";
// $filename = uniqid().basename($_FILES["fileToUpload"]["name"]);

$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = uniqid() . '.' . end($temp);

$target_file = $target_dir . $newfilename;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $error = "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    $error = "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error

if ($uploadOk == 0) {
    sendResponse(0,__LINE__, $error);

// if everything is ok, try to upload file

} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {


        $stmt = $db->prepare('CALL uploadPost(:user, :timestamp, :title, :url)');
        $stmt->bindParam(':title', $sTitle);
        $stmt->bindValue(':url', $newfilename);
        $stmt->bindParam(':user', $iUserId);
        $stmt->bindParam(':timestamp', $iTimestamp);

        if($stmt->execute()) {
            header('Location: ../home.php');
        }
    } else {
        sendResponse(0,__LINE__, "Sorry there was a problem with uploading of your post");
    }
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