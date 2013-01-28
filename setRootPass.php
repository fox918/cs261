<?php
//setRootPass.php?passwd=1234


//get the DB login data
require_once 'config.php';
require_once 'classes.php';


//check if INSTALL flag == 1
if(INSTALL!=1){ 
    $errorTitle='Das Root passwort konnt nicht gesetzt werden';
    $error='Die Konstante INSTALL ist nicht richtig gesetzt, überprüfe die config.php.';
    include './templates/error.php';
die;
}

$pw = $_REQUEST["passwd"];

$hash = md5($pw.GLOBAL_SALT);

$db = new Database();
$db->run("UPDATE users SET uPw='$hash' where uName='root'")
echo "neues Passwort gesetzt"
?>
