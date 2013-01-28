<?php
/*
 * post/get parameters:
 * new_name : The name of the user to create
 * new_pw : The password
 * new_type : The section (Verwaltung, Arbeiter, Lager)
 *              Verwaltung can make new users and reset passwords
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


if(isset($_REQUEST["new_name"]) && isset($_REQUEST["new_pw"]) && isset($_REQUEST["new_type"]))
{
    $name = $db->escape($_REQUEST["new_type"]);
    $pw = md5($_REQUEST["new_pw"].GLOBAL_SALT);
    $type = $_REQUEST["new_type"];
    $uid = $db->escape($user->getId());
    
    switch($type)
    {
        case "Verwaltung":
            $type="admin";
            break;
        case "Lager":
            $type="store";
            break;
        case "Arbeiter":
            $type="worker";
            break;
        default:
            $outputMsgs["errors"] = "true";
            array_push($errorMsgs, "Unbekannter Typ");
            $outputMsgs['errormsgs'] = $errorMsgs;
            echo json_encode($outputMsgs);
            die();  
    }
    

    $ret = $db->run("select uType from users where uId='$uid'");
    $row = $ret->fetch_assoc();
    
    //only admins are allowed to make new users
    if(isset($row["uType"]) && strcasecmp($row["uType"], "admin") == 0)
    {
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into users (uName, uPw, uType)
                          values ('$name', '$pw', '$type')");
        
        $outputMsgs["errors"] = "false";
        echo json_encode($outputMsgs);
        die();
    }
    else 
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Nur die Verwaltung kann neue Accounts erstellen");
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
