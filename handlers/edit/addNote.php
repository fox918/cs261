<?php
/*
 * post/get parameters:
 * cr_id : the id of the job
 * cr_note_title : the note title
 * cr_note : the note itself
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


if(isset($_REQUEST["cr_id"]) && isset($_REQUEST["cr_note_title"]) && isset($_REQUEST["cr_note"]))
{
    $id = $db->escape($_REQUEST["cr_id"]);
    $uid = $db->escape($user->getId());
    
    /*<from tinymce.com>*/
    $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
    $allowedTags.='<li><ol><ul><span><div><br><ins><del>';
    $note = $db->escape(strip_tags(stripslashes($_REQUEST["cr_note"]),$allowedTags));
    /*</from tinymce.com>*/
    
    $title = $db->escape(filter_var($_REQUEST["cr_note_title"], FILTER_SANITIZE_STRING));
    $ret = $db->run("select * from jobs where jId = '$id' and jResp = '$uid'");
    $row = $ret->fetch_assoc();
    if(isset($row["jId"]))
    {
        if(strlen($note) == 0 && strlen($title) == 0)
        {
            $outputMsgs["errors"] = "true";
            array_push($errorMsgs, "Kein Text gefunden");
            $outputMsgs['errormsgs'] = $errorMsgs;
            echo json_encode($outputMsgs);
            die();
        }
        date_default_timezone_set('Europe/Zurich');
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into comText (coTitle, coText, coDate, jobs_jId, users_uId)
                          values ('$title', '$note', '$datetime', '$id', '$uid')");
        
        $uname = $user->getUsername();
        $datetime = date("Y-m-d  H:i:s",time());
        $db->run("insert into history (hTime, hType, hText, jobs_jId)
            values ('$datetime', 'Neue Notiz', '$uname hat eine neue Notiz mit dem Titel \"$title\" hinzugefügt.', '$id')");
        
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
