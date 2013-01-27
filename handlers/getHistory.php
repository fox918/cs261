<?php
/*
 * returns the history of the job with the provided ID
 * post/get parameters:
 * cr_id : the id
 * useXML : if this is set, then it's returned as XML and not as JSON
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
$jid = $db->escape($_REQUEST["cr_id"]);
$id = $db->escape($user->getId());

$ret = $db->run("select * from jobs where jResp='$id' and jId='$jid'");
$row = $ret->fetch_assoc();
if(!isset($row["jId"]))
{
    $outputMsgs["errors"] = "true";
    array_push($errorMsgs, "Ungültige ID");
    $outputMsgs['errormsgs'] = $errorMsgs;
    echo json_encode($outputMsgs);
    die();
}

$ret = $db->run("select hTime, hType, hText from history where jobs_jId='$jid'");
$table;
$i = 0;
while($row = $ret->fetch_assoc())
{
    $table["$i"]["Zeit"] = $row["hTime"];
    $table["$i"]["Titel"] = $row["hType"];
    $table["$i"]["Beschreibung"] = $row["hText"];
    $i++;
}
if(isset($_REQUEST["useXML"]))
{
    $xml = new SimpleXMLElement('<history/>');
    
    $snode = $xml->addChild("dr");
    $snode->addChild("dh","Zeit");
    $snode->addChild("dh","Titel");
    $snode->addChild("dh","Beschreibung");
    foreach($table as $key => $value)
    {
        $snode = $xml->addChild("dr");
        $snode->addChild("dc",$value["Zeit"]);
        $snode->addChild("dc",$value["Titel"]);
        $snode->addChild("dc",$value["Beschreibung"]);
    }
    $arr = explode("\n", $xml->asXML());
    
    $ret = $arr[0]."\n<?xml-stylesheet href=\"/css/xml.css\" type=\"text/css\"?>\n".$arr[1]."\n";
    
    header("Content-Type: application/xml");
    print $ret;
    
    
    die();
}



echo json_encode($table);
?>
