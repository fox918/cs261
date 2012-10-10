<?php
/*
 * important things:
 * compression, sessionhandler
 */
ob_start("ob_gzhandler"); //compression
session_start(); 

///DEBUG
error_reporting(E_ALL);
ini_set('display_errors', '1');
///DEBUG


/*
 * Includes
 */
include_once 'classes.php';

/*
 * Processing of GET-Requests
 * Following get requests exist:
 * Name     Action
 * 
 * page     display the designated page
 * subpage  display the designated subpage of the page
 * order    specify an order for the page
 * action   do something (eg. logout)
 * 
 * Example:
 * index.php?page=edit&order=123    Edit order nr. 123
 * index.php?action=logout          log me out
 */

/*
 * initialize user
 */
$user = new user();

/*
 * initialize page
 */
$page = new page();
$page->setUser($user);
if(isset($_GET['page']))
{
    $page->setPage($_GET['page']);
}

/*
 * POST-Requests
 * need to decide wether do work with ajax(-> separate post handling) or
 * to do it here
 */


?>


<!doctype html>

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

    <title><?php $page->printTitle() ?></title>
</head>

<body onload="setTimeout('location.reload(true)',5000)">

    <div id="content"> 
        <header>
        <div>
            <div id="logo">
                <img src="./img/logo.png" alt="logo" />
                <h1>Auftragverwaltung</h1>
            </div>
            <div id="logininfo">
            <p id="username"><?php $user->printUsername() ?></p>
            <p id="department"><?php $user->printDepartment() ?></p>
                <?php if($user->isLoggedIn()) { ?>
                    <form action="./index.php?action=logout" method="post">
                        <input type="submit" value="Ausloggen" />
                    </form>
                <?php } ?>
            </div>
        </div>
        <div style="clear:both"></div>
        <nav>
        <ul>
            <li><a class="active" href="index.php?page=create">Erfassung</a></li>
            <li><a href="index.php?page=list">Aufträge</a></li>
            <li><a href="index.php?page=archive">Archiv</a></li>
            <li><a href="index.php?page=admin">Verwaltung</a></li>
        </ul>
        </nav>
        </header>

        <article id="create">
        <fieldset>
            <legend>Kunde</legend>
            asfasf
        </fieldset>
        <fieldset>
            <legend>Auftrag</legend>

        </fieldset>
        <fieldset>
            <legend>Kalender</legend>
        </fieldset>
        <fieldset>
            <legend>angehängte Dateien</legend>
        </fieldset>
        <div class="control">
            <button type="submit">Erstellen</button>
        </div>
        </article>

        <aside>

        </aside>

        <footer>

        </footer>

    </div>

    <!-- Javascript -->
<script src='./js/jquery-1.8.2.min.js'></script>
<script src="./js/main.js"></script>

</body>
    </html>
