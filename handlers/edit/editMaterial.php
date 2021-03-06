<?php
/*
 * post/get parameters:
 * cr_mat_id : the id of the material
 * cr_mat_count : How many pieces
 * cr_mat_title : material name
 * cr_mat_note : material description
 * cr_mat_state : material status (Bestellt, Geliefert or Benutzt)
 * cr_mat_delivery : Delivery date
 * cr_mat_price : Costs
*/

require_once '../../db.php';
require_once '../../classes.php';

date_default_timezone_set('Europe/Zurich');
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

if(isset($_REQUEST["cr_mat_id"]) 
        && isset($_REQUEST["cr_mat_count"])
        && isset($_REQUEST["cr_mat_note"]) 
        && isset($_REQUEST["cr_mat_state"]) 
        && isset($_REQUEST["cr_mat_delivery"]) 
        && isset($_REQUEST["cr_mat_price"]) 
        && isset($_REQUEST["cr_mat_title"]))
{
    $fid = $db->escape($_REQUEST["cr_mat_id"]);
    $uid = $db->escape($user->getId());
    
    $ret = $db->run("select jobs_jId from materials where mId = '$fid'");
    $row = $ret->fetch_assoc();
    if(!isset($row["jobs_jId"]))
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Das Material konnte nicht gefunden werden (ungültige ID)");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    $id = $row["jobs_jId"];
    
    $ret = $db->run("select * from jobs where jId = '$id' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    
    $title = $db->escape(filter_var($_REQUEST["cr_mat_title"], FILTER_SANITIZE_STRING));
    $note = $db->escape(filter_var($_REQUEST["cr_mat_note"], FILTER_SANITIZE_STRING));
    $state = $_REQUEST["cr_mat_state"];
    switch($state)
    {
        case "Bestellt":
            $state = "order";
            break;
        case "Geliefert":
            $state = "arrived";
            break;
        case "Benutzt":
            $state = "used";
            break;
        default:
            $outputMsgs["errors"] = "true";
            array_push($errorMsgs, "Ungültiger Materialstatus");
            $outputMsgs['errormsgs'] = $errorMsgs;
            echo json_encode($outputMsgs);
            die();
    }
    
    
    
    $delivery = strtotime($_REQUEST["cr_mat_delivery"]);
    if($delivery == false)
    {
        $outputMsgs["errors"] = "true";
        array_push($errorMsgs, "Unbekanntest Zeitformat.");
        $outputMsgs['errormsgs'] = $errorMsgs;
        echo json_encode($outputMsgs);
        die();
    }
    $delivery = date("Y-m-d  H:i:s",$delivery);
    
    $count = $db->escape(filter_var($_REQUEST["cr_mat_count"], FILTER_SANITIZE_NUMBER_INT));
    $price = $db->escape(filter_var($_REQUEST["cr_mat_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    
    if(isset($row["jId"]))
    {
        if(strlen($title) == 0 || strlen($price) == 0 || strlen($count) == 0)
        {
            $outputMsgs["errors"] = "true";
            array_push($errorMsgs, "Zu wenig Text eingegeben");
            $outputMsgs['errormsgs'] = $errorMsgs;
            echo json_encode($outputMsgs);
            die();
        }
        date_default_timezone_set('Europe/Zurich');
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("update materials set 
            mName='$title', mDesc='$note', mState='$state', mDelDate='$delivery', mPrice='$price', mQuantity='$count'
            where mId = '$fid'");
        
        $uname = $user->getUsername();
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into history (hTime, hType, hText, jobs_jId)
            values ('$datetime', 'Material aktualisiert', '$uname hat eine Material aktualisiert.', '$id')");

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
