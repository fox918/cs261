<?php
/*
 * returns all jobs assigned to the current user
 * post/get parameters:
 * none
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

$id = $db->escape($user->getId());

$ret = $db->run("select * from jobs where jResp='$id'");
$table;
while($row = $ret->fetch_assoc())
{
    $temp;
    $jid = $row["jId"];
    $cid = $row["clients_cId"];
    $temp["jName"] = $row["jName"];
    $temp["jDesc"] = $row["jDesc"];
    $temp["jStage"] = $row["jStage"];
    $temp["jCreationDate"] = $row["jCreationDate"];
    
    $rr = $db->run("select * from clients where cId='$cid'");
    
    while($r = $rr->fetch_assoc())
    {
        $temp["client"]["cName"] = $r["cName"];
        $temp["client"]["cType"] = $r["cType"];
        $temp["client"]["cGender"] = $r["cGender"];
        $temp["client"]["cPhone"] = $r["cPhone"];
        $temp["client"]["cMobile"] = $r["cMobile"];
        $temp["client"]["cStreet"] = $r["cStreet"];
        $temp["client"]["cCity"] = $r["cCity"];
    }
    
    $rr = $db->run("select * from clients where cId='$cid'");
    while($r = $rr->fetch_assoc())
    {
        $temp["client"]["cName"] = $r["cName"];
        $temp["client"]["cType"] = $r["cType"];
        $temp["client"]["cGender"] = $r["cGender"];
        $temp["client"]["cPhone"] = $r["cPhone"];
        $temp["client"]["cMobile"] = $r["cMobile"];
        $temp["client"]["cStreet"] = $r["cStreet"];
        $temp["client"]["cCity"] = $r["cCity"];
    }
    
    $rr = $db->run("select * from comText where jobs_jId='$jid'");
    while($r = $rr->fetch_assoc())
    {
        $nid = $r["coTextId"];
        $temp["notes"]["$nid"]["coTitle"] = $r["coTitle"];
        $temp["notes"]["$nid"]["coText"] = $r["coText"];
        $temp["notes"]["$nid"]["coDate"] = $r["coDate"];
        $temp["notes"]["$nid"]["coChange"] = $r["coChange"];
    }
    
    $rr = $db->run("select * from shedule where jobs_jId='$jid'");
    while($r = $rr->fetch_assoc())
    {
        $nid = $r["sId"];
        $temp["shedule"]["$nid"]["sStart"] = $r["sStart"];
        $temp["shedule"]["$nid"]["sStop"] = $r["sStop"];
        $temp["shedule"]["$nid"]["sComment"] = $r["sComment"];
    }
    
    $rr = $db->run("select * from comAttach where jobs_jId='$jid'");
    while($r = $rr->fetch_assoc())
    {
        $nid = $r["coAtId"];
        $temp["attachments"]["$nid"]["coDate"] = $r["coDate"];
        $temp["attachments"]["$nid"]["coChange"] = $r["coChange"];
        $temp["attachments"]["$nid"]["coResource"] = $r["coResource"];
    }
    
    
    $rr = $db->run("select * from materials where jobs_jId='$jid'");
    while($r = $rr->fetch_assoc())
    {
        $nid = $r["mId"];
        $temp["materials"]["$nid"]["mName"] = $r["mName"];
        $temp["materials"]["$nid"]["mDesc"] = $r["mDesc"];
        $temp["materials"]["$nid"]["mState"] = $r["mState"];
        $temp["materials"]["$nid"]["mDelDate"] = $r["mDelDate"];
        $temp["materials"]["$nid"]["mPrice"] = $r["mPrice"];
        $temp["materials"]["$nid"]["mQuantity"] = $r["mQuantity"];
    }

    $table[$jid] = $temp;
}

$table["errors"] = false;
echo json_encode($table);
?>
