<?php
/*
 * post/get parameters:
 * cr_id : the job's id
 * action : change this job's state (evaluation, processing, billing, finished)
*/

require_once '../../db.php';
require_once '../../classes.php';

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

if(isset($_REQUEST["cr_id"]) 
        && isset($_REQUEST["action"]))
{
    $id = $db->escape($_REQUEST["cr_id"]);
    $state = $db->escape($_REQUEST["action"]);
    $uid = $db->escape($user->getId());
    
    switch($state)
    {
        case "evaluation":
        case "processing":
        case "billing":
        case "finished":
            break;
        default:
            $outputMsgs["errors"] = "true";
            array_push($errorMsgs, "Unbekanter Status: ".$state);
            $outputMsgs['errormsgs'] = $errorMsgs;
            echo json_encode($outputMsgs);
            die();
    }
    
    $ret = $db->run("select * from jobs where jId = '$id' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    
    if(isset($row["jId"]))
    {
        $db->run("update jobs set 
                jStage='$state'
                where jId = '$id'");
        $outputMsgs["errors"] = "false";
        echo json_encode($outputMsgs);
        die();
    }
    else 
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Ungültige Auftrags ID angegeben");
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
