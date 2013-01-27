<?php
/*
 * post/get parameters:
 * cr_date_id : the id of the date to edit
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


if(isset($_REQUEST["date"]) 
        && isset($_REQUEST["date_statime"]) 
        && isset($_REQUEST["date_stotime"])
        && isset($_REQUEST["cr_date_id"])
        && isset($_REQUEST["date_desc"]))
{
    date_default_timezone_set('Europe/Zurich');
    $uid = $db->escape($user->getId());
    $eid = $db->escape($_REQUEST["cr_date_id"]);
    $ret = $db->run("select * from shedule where sId = '$eid'");
    $row = $ret->fetch_assoc();
    
    if(!isset($row["jobs_jId"]))
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Kommentar nicht gefunden.");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    
    $id = $row["jobs_jId"];
    
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
        
        $db->run("update shedule set 
            sStart='$date $start', sStop='$date $stop', sComment='$desc', users_uId='$uid'
            where sId = '$eid'");
        
        $uname = $user->getUsername();
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into history (hTime, hType, hText, jobs_jId)
            values ('$datetime', 'Kalendereintrag aktualisiert', '$uname hat einen Kalendereintrag aktualisiert.', '$id')");

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
