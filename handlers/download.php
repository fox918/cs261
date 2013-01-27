<?php
/*
 * post/get parameters:
 * id : the file id
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

if(isset($_REQUEST["id"]))
{
    $id = $db->escape($_REQUEST["id"]);
    
    $ret = $db->run("select * from comAttach where coAtId = '$id'");
    $row = $ret->fetch_assoc();
    
    if(!isset($row["jobs_jId"]))
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Datei nicht gefunden");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    
    $fname = "../uploads/".$row["coAtId"];
    $retname = $row["coResource"];
    $jid = $row["jobs_jId"];
    $uid = $db->escape($user->getId());
    
    $ret = $db->run("select * from jobs where jId = '$jid' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    
    if(isset($row["jId"]) && file_exists($fname))
    {
        $handle = fopen($fname, "r");
        if($handle == false)
        {
            $outputMsgs["errors"] = "true";
            array_push($errorMsgs, "Datei konnte nicht gelesen werden");
            $outputMsgs['errormsgs'] = $errorMsgs;
            echo json_encode($outputMsgs);
            die();
        }
        //setting header to download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$retname);
        header('Content-Transfer-Encoding: chunked');
        while(($retb = fread($handle, 0x7FFF)) != false)
        {
            echo $retb;
        }
        die();
    }
    else 
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Datei nicht gefunden");
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
