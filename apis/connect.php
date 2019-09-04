<?php

try{

  $sUserName = 'root'; // DO NOT EVER USE ROOT IN REAL LIFE
  $sPassword = '';
  $sConnection = "mysql:host=localhost; dbname=Instagram_main; charset=utf8mb4";
  
  $aOptions = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ // FETCH_ASSOC
  );
  $db = new PDO( $sConnection, $sUserName, $sPassword, $aOptions );
}catch( PDOException $e){
  echo $e->getMessage();
  // echo '{"status":0,"message":"cannot connect to database"}';
  exit;
}

