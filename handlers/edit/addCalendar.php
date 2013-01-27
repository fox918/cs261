<?php
/*
 * post/get parameters:
 * cr_id : the id of the job
 * date : Date
 * date_statime : start time
 * date_stotime : stop time
 * date_desc : description
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
        && isset($_REQUEST["date"]) 
        && isset($_REQUEST["date_statime"]) 
        && isset($_REQUEST["date_stotime"]) 
        && isset($_REQUEST["date_desc"]))
{
    date_default_timezone_set('Europe/Zurich');
    $id = $db->escape($_REQUEST["cr_id"]);
    $uid = $db->escape($user->getId());
    $ret = $db->run("select * from jobs where jId = '$id' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    
    $date = strtotime($_REQUEST["date"]);
    if($date == false)
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Unbekanntest Datumsformat.");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    $date = date("Y-m-d",$date);
    
    $start = strtotime($_REQUEST["date_statime"]);
    if($start == false)
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Unbekanntest Zeitformat.");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    $start = date("H:i:s",$start);
    
    $stop = strtotime($_REQUEST["date_stotime"]);
    if($stop == false)
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Unbekanntest Zeitformat.");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    $stop = date("H:i:s",$stop);
    $desc = $db->escape(filter_var($_REQUEST["date_desc"], FILTER_SANITIZE_STRING));
    
    
    if(isset($row["jId"]))
    {
        if(strlen($desc) == 0)
        {
            $outputMsgs["errors"] = "true";
            array_push($errorMsgs, "Beschreibung zu kurz");
            $outputMsgs['errormsgs'] = $errorMsgs;
            echo json_encode($outputMsgs);
            die();
        }
        $db->run("insert into shedule (sStart, sStop, sComment, jobs_jId, users_uId) 
            values ('$date $start', '$date $stop', '$desc', '$id', '$uid')");

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
