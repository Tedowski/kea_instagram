<?php

$iPostId = $_GET['iPostId'];

$jObj = new stdClass();
$jObj->id = $iPostId;

echo json_encode($jObj);