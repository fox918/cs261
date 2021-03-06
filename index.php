<?php
/*
 * important things:
 * compression, sessionhandler
 */
ob_start("ob_gzhandler"); //compression
session_start(); //session mgmt 

/*
 * Includes
 */
require_once 'config.php';//constants
require_once 'db.php'; //db class
require_once 'classes.php'; //all the other classes
date_default_timezone_set('Europe/Zurich');
/*
 * Check requirements
 */
//thou shalt not enter with Internet Exploder
if (eregi("MSIE",getenv("HTTP_USER_AGENT")) ||
    eregi("Internet Explorer",getenv("HTTP_USER_AGENT"))) {
        //DANGER DANGER, IS USING INTERNET EXPLORER, BACK OFF
        //DANGER DANGER DANGER
        Header("Location: ./img/bad_ie.jpg");
        //DANGER DANGER DANGER
	die;
}

//check for install flag
if(INSTALL==1)
{
    //INSTALL flag set, db probably not set up
    echo 'Please install the database first or check your config.php';
    die;
}
    //TODO maybe check if DB connection works


/*
 * Please note all the Requests you are creating in here:
 *
 ****************
 * GET-Requests:
 * Name     Action
 * page     display the designated page
 * subpage  display the designated subpage of the page
 * order    specify an order for the page
 * action   do something (eg. logout)
 * error    error message of the error which happened
 * sortby   which topic is used for sorting
 * search   searchterm
 *
 * Example:
 * index.php?page=edit&order=123    Edit order nr. 123
 * index.php?action=logout          log me out
 * 
 * Values:
 *      page        create, edit, list, archive, admin, login
 *      subpage     
 *      action      logout
 *      order       numerical
 *      sortby      name, city, title, number, creation, change
 *      search      alphanumeric 
 *
 ****************
 * SESSION variables:
 * Name     Action 
 * user     username of the momentary user
 * auth     authentication token
 *
 ****************
 * COOKIE variables:
 * Name     Action
 * 
 ****************
 * POST variables:
 * Name             Action
 * login_username   username for login
 * login_password   password for login
 *
 *
 */

/*
 * Actions
 */
if(isset($_GET['action']))
{
    switch($_GET['action']){
        case 'logout': //do a logout, destroy session and reload
            session_destroy();
            header('Location: index.php');
            die; //live and let die
            break;
    }
}

/*
 * initialize user
 */
$user = new user();

//either user is logged in or needs to do so:
if(isset($_SESSION['user']) && isset($_SESSION['auth']))
{
    //user already logged in
    $user->authenticate($_SESSION['user'], $_SESSION['auth']);
    if($user->isLoggedIn() == false)
    {
        if(isset($_POST['login_username']) && isset($_POST['login_password']))
        {
            $user->login($_POST['login_username'],$_POST['login_password']);
        }
        //set sessionvars
        if($user->isLoggedIn())
        {
            $_SESSION['user'] = $user->getUsername();
            $_SESSION['auth'] = $user->getAuthToken();
        }
    }
} else {
    //user is not logged in
    //try login
    if(isset($_POST['login_username']) && isset($_POST['login_password']))
    {
        $user->login($_POST['login_username'],$_POST['login_password']);
    }
    //set sessionvars
    if($user->isLoggedIn())
    {
        $_SESSION['user'] = $user->getUsername();
        $_SESSION['auth'] = $user->getAuthToken();
    }
}
/*
 * initialize page
 */
$page = new page();
$page->setUser($user);
if(isset($_GET['page']))
{
    $page->setPage($_GET['page']);
}else{
        $page->setPage(NULL);
}

if(isset($_GET['order'])){
    $page->setOrder($_GET['order']);
}
//TODO setSubPage ...

/*
 * POST-Requests
 * need to decide wether do work with ajax(-> separate post handling) or
 * to do it here
 */


?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="title" content="Auftragsverwaltung">
    <meta name="description" content="eine Auftragsverwaltung für KMU">
    <meta name="author" content="Fox">

    <!-- IE and chrome fix -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!-- Dublin Core Metadata : http://dublincore.org/ -->
    <meta name="DC.title" content="Auftragsverwaltung">
    <meta name="DC.subject" content="Auftragsveraltung für KMU">
    <meta name="DC.creator" content="Fox">

    <!-- Favicon -->
    <link rel="shortcut icon" href="./img/favicon.ico">
    <!-- Humans -->
    <link rel="humans" href="./humans.txt">

    <!-- Stylesheet -->
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/jquery.superbox.css">

        <title><?php $page->printTitle() ?></title>
    </head>

    <body>
      <!--onload="setTimeout('location.reload(true)',5000)" 
            autoreload for debugging css, insert in body tag -->
        <div id="content"> 
            <header>
            <div>
                <div id="logo">
                    <img src="./img/logo.png" alt="logo" />
                    <h1>Auftragverwaltung</h1>
                </div>
                <div id="logininfo">
                <p id="username"><?php echo $user->getUsername(); ?></p>
                <p id="department"><?php echo $user->getDepartment(); ?></p>
                    <?php if($user->isLoggedIn()) { ?>
                        <form action="./index.php?action=logout" method="post">
                            <input type="submit" value="Ausloggen" />
                        </form>
                    <?php } ?>
                </div>
            </div>
            <div style="clear:both"></div>
            <nav>
                <?php $page->printMenu() ?>
            </nav>
            </header>

            <div id="notifications">
                <!-- different messages: 
                <div class="info">Info message</div>
                <div class="success">success message</div>
                <div class="warning">warning message</div>
                <div class="info">error message</div>
                -->
            </div>

            <?php $page->printArticle(); ?>

            <footer>
            <?php $page->printFooter(); ?>
            </footer>

        </div>

        <!-- Javascript -->
    <script type="text/javascript" src='./js/jquery-1.8.2.min.js'></script>
    <script type="text/javascript" src='./js/tiny_mce/tiny_mce.js'></script>
    <script type="text/javascript" src='./js/tiny_mce/jquery.tinymce.js'></script>
    <script type="text/javascript" src='./js/superbox/jquery.superbox-min.js'></script>
    <script type="text/javascript" src='./js/file/jquery.ajaxfileupload.js'></script>
    <script type="text/javascript" src="./js/main.js"></script>
    <?php $page->includeJS() ?> 
</body>
</html>
