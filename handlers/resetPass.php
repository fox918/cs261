<?php
/*
 * post/get parameters:
 * uid : The user's ID
 * 
 * only people of the type "Verwaltung" can reset passwords of other users
*/

require_once '../db.php';
require_once '../classes.php';

session_start();
$user = new user();

$outputMsgs = array("errors" => false); //no messages yet, no errors either
$errorMsgs = array();

//either user is logged in or needs to do so:
if(isset($_SESSION['user']) && isset($_SESSION['auth']))
{
    //user needs to be authenticate
    if( !$user->authenticate($_SESSION['user'], $_SESSION['auth'])){
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Autentifizierung fehlgeschlagen");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
} else {
    //user is not logged in
    $outputMsgs["errors"] = "true";
    array_push($errorMsgs, "Nicht eingeloggt");
    $outputMsgs['errormsgs'] = $errorMsgs;
    echo json_encode($outputMsgs);
    die();
}


$db = new Database();


if(isset($_REQUEST["uid"]))
{
    $id = $db->escape($_REQUEST["uid"]);
    
    //our completely random new password generator *cough*
    $pw = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
    $pwHash = md5($pw.GLOBAL_SALT);
    $uid = $db->escape($user->getId());

    $ret = $db->run("select uType from users where uId='$uid'");
    $row = $ret->fetch_assoc();
    
    //only admins are allowed to make new users
    if(isset($row["uType"]) && strcasecmp($row["uType"], "admin") == 0)
    {
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("update users set uPw='$pwHash' where uId='$id'");
        
        $outputMsgs["errors"] = "false";
        $outputMsgs["new_pass"] = $pw;
        echo json_encode($outputMsgs);
        die();
    }
    else 
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Nur die Verwaltung kann Passwörter zurücksetzen");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
}
$outputMsgs["errors"] = "true";
array_push($errorMsgs, "Es sind nicht alle Parameter gesetzt");
$outputMsgs['errormsgs'] = $errorMsgs;
echo json_encode($outputMsgs);
die();
?>
