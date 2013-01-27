<?php
/*
 * post/get parameters:
 * cr_id : the job's id
 * cr_desc : the description
 * cr_resp : the person responsible to handle it
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
        && isset($_REQUEST["cr_resp"]) 
        && isset($_REQUEST["cr_desc"]))
{
    $id = $db->escape($_REQUEST["cr_id"]);
    $resp = $db->escape($_REQUEST["cr_resp"]);
    $uid = $db->escape($user->getId());
    
    
    $ret = $db->run("select uId from users where uName = '$resp'");
    $row = $ret->fetch_assoc();
    if(!isset($row["uId"]))
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Der Nutzer konnte nicht gefunden werden (ungültiger Name)");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    $respId = $row["uId"];
    
    $ret = $db->run("select * from jobs where jId = '$id' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    
    /*<from tinymce.com>*/
    $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
    $allowedTags.='<li><ol><ul><span><div><br><ins><del>';
    $note = $db->escape(strip_tags(stripslashes($_REQUEST["cr_desc"]),$allowedTags));
    /*</from tinymce.com>*/
    
    if(isset($row["jId"]))
    {
        $db->run("update jobs set 
                jDesc='$note', jResp='$respId' 
                where jId = '$id'");
        
        $uname = $user->getUsername();
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into history (hTime, hType, hText, jobs_jId)
            values ('$datetime', 'Auftrag aktualisiert', '$uname hat einen Auftrag aktualisiert.', '$id')");
        
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
