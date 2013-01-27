<?php
/*
 * post/get parameters:
 * cr_id : the id of the job
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

if(isset($_REQUEST["cr_id"]))
{
    date_default_timezone_set('Europe/Zurich');
    $id = $db->escape($_REQUEST["cr_id"]);
    $uid = $db->escape($user->getId());
    $ret = $db->run("select * from jobs where jId = '$id' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    
    $_FILES = array_pop($_FILES);

    if(!isset($_FILES['error']) || $_FILES['error'] != UPLOAD_ERR_OK)
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Upload der Datei fehlgeschlagen oder keine Datei hochgeladen ");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    $fname = $db->escape(filter_var($_FILES['name'], FILTER_SANITIZE_STRING));
    $datetime = date("Y-m-d  H:i:s",time());
    
    if(isset($row["jId"]))
    {
        $db->run("insert into comAttach (coResource, coDate, users_uId, jobs_jId)
              values ('$fname', '$datetime', '$uid', '$id')");

        $ret = $db->run("select max(coAtId) from comAttach");
        $fid = $ret->fetch_assoc()["max(coAtId)"];
        
        move_uploaded_file($_FILES['tmp_name'], "../../uploads/$fid"); 
        
        $uname = $user->getUsername();
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into history (hTime, hType, hText, jobs_jId)
            values ('$datetime', 'Neue Datei', '$uname hat eine neue Datei hochgeladen: \"$fname\".', '$id')");

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
