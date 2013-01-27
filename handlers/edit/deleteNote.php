<?php
/*
 * post/get parameters:
 * cr_id : the id of the job
 * cr_note_id : the id of the note to remove
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


if(isset($_REQUEST["cr_id"]) && isset($_REQUEST["cr_note_id"]))
{
    $id = $db->escape($_REQUEST["cr_id"]);
    $uid = $db->escape($user->getId());
    $nid = $db->escape($_REQUEST["cr_note_id"]);
    $ret = $db->run("select * from jobs where jId = '$id' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    if(isset($row["jId"]))
    {
        $db->run("DELETE FROM comText WHERE jobs_jId = '$id' and coTextId = '$nid'");
        
        $uname = $user->getUsername();
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into history (hTime, hType, hText, jobs_jId)
            values ('$datetime', 'Notiz entfernt', '$uname hat eine Notiz gelöscht.', '$id')");
        
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
