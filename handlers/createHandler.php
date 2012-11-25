<?php
/* Handler for the create formular of the website
 */

/*
 * Includes
 */
require_once '../config.php';//constants
require_once '../db.php'; //db class
require_once '../classes.php'; //all the other classes



/*
 * check if user is logged in
 */
session_start();
$user = new user();

//either user is logged in or needs to do so:
if(isset($_SESSION['user']) && isset($_SESSION['auth']))
{
    //user needs to be authenticate
    if( !$user->authenticate($_SESSION['user'], $_SESSION['auth'])){
        // TODO die;   
    }
} else {
    //user is not logged in
        //TODO die;
}


//insert data into DB

$outputMsgs = array("errors" => false); //no messages yet, no errors either
$errorMsgs = array();

$order = new newOrder();
$success;
$err;
$order->processAll($success, $err);

$outputMsgs["errors"] = !$success;
array_push($errorMsgs, "$err");


$outputMsgs['errormsgs'] = $errorMsgs;
echo json_encode($outputMsgs);
