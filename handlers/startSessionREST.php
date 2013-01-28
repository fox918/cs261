<?php

/*
 * post/get parameters
 * name: username
 * passwd: the password
 */

require_once '../classes.php';
require_once '../config.php';
$outputMsgs = array("errors" => false); //no messages yet, no errors either
$errorMsgs = array();
session_start();
$outputMsgs["PHPSESSID"] = "";

if(isset($_REQUEST["name"]) && isset($_REQUEST["passwd"]))
{
    
    $usr = new user();
    if($usr->login($_REQUEST["name"], $_REQUEST["passwd"]) == true)
    {
        if($usr->isLoggedIn())
        {
            $_SESSION['user'] = $usr->getUsername();
            $_SESSION['auth'] = $usr->getAuthToken();
            $outputMsgs["PHPSESSID"] = session_id();
        }
    }
    else 
    {
        array_push($errorMsgs, "Login fehlgeschlagen");
    }
}
else
{
    array_push($errorMsgs, "Nocht alle Parameter sind mitgegeben worden");
}
$outputMsgs['errormsgs'] = $errorMsgs;
echo json_encode($outputMsgs);
?>
