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

/*
 * Check requirements
 */
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
 *
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
//TODO setSubPage setOrder...

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

            <?php $page->printArticle(); ?>

            <footer>

            </footer>

        </div>

        <!-- Javascript -->
    <script type="text/javascript" src='./js/jquery-1.8.2.min.js'></script>
    <script type="text/javascript" src='./js/tiny_mce/tiny_mce.js'></script>
    <script type="text/javascript" src='./js/tiny_mce/jquery.tinymce.js'></script>
    <script type="text/javascript" src="./js/main.js"></script>
    <script type="text/javascript">
    //jQuery
$(function() {
    $("textarea").tinymce({
      script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',
          theme : "simple"
    });
    function makeDOM(html){
        var wrapper= document.createElement('div');
        wrapper.innerHTML= html;
        return wrapper.firstChild;    
    }

    //counters
    var materials=1;
    var notes=1;
    var dates=1;
    var files=1;

        //add more material
    $("#cr_mat_addfield").click(function() {
            var html="<p class='material' id='mat_##NUMBER##'>" +
            "<input style='width:70px' type='text' name='cr_mat_count_##NUMBER##' />" +
            "<input style='left:80px' type='text' name='cr_mat_title_##NUMBER##' />" +
            "<input style='left:270px' type='text' name='cr_mat_note_##NUMBER##' />" +
            "<select style='left:460px' name='cr_mat_state_##NUMBER##'>" +
                "<option>Bestellt</option>" +
                "<option>Geliefert</option>" +
                "<option>Benutzt</option>" +
            "</select>" +
            "<input style='left:560px;width:120px' type='text' name='cr_mat_delivery_##NUMBER##' />" +
            "<input style='left:700px;width:100px' type='text' name='cr_mat_price_##NUMBER##'/>" +
            "<img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().remove()' />" +
            "</p>";
            materials++;
            html = html.replace(/##NUMBER##/g,""+materials);
            var dom=makeDOM(html);
            $("#materials>div").append(dom);
        return false;
        });

        //add more notes
        $("#cr_note_addfield").click(function() {
            var html = "<div class='note' id='note_##NUMBER##'>" +
            "<fieldset>" +
                "<legend>" +
                    "<input type='text' name='cr_note_title_##NUMBER##' />" +
                    "<img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().parent().remove()' />" +
                "</legend>" +
                "<textarea name='cr_note_##NUMBER##' rows='13' cols='40'></textarea>" +
            "</fieldset></div>";
            notes++;
            html = html.replace(/##NUMBER##/g,""+notes);
            var dom=makeDOM(html);
            $("#notes>div").append(dom);
            $(dom).find("textarea").tinymce({
                script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',
                theme : "simple"
            });
            return false;
        });

        //add more appointments
        $("#cr_date_addfield").click(function() {
                    var html ="<fieldset  id='date_##NUMBER##' class='date'>"+
"                <legend>"+
"                    Datum <input type='text' name='cr_date_##NUMBER##'/>"+
"            <img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().parent().remove()' />" +
"                </legend>"+
"               <p>"+
"            <span>"+
"                <label for='cr_date_statime_##NUMBER##'>Startzeit</label>"+
"                <input type='text' name='cr_date_statime_##NUMBER##' id='cr_date_statime_##NUMBER##'/>"+
"            </span>"+
"            <span>"+
"                <label for='cr_date_stotime_##NUMBER##'>bis um</label>"+
"                <input type='text' name='cr_date_stotime_##NUMBER##' id='cr_date_stotime_##NUMBER##'/>"+
"            </span>"+
"            <span>"+
"                <label for='cr_date_desc_##NUMBER##'>Notiz</label>"+
"                <input type='text' name='cr_date_desc_##NUMBER##' id='cr_date_desc_##NUMBER##'/>"+
"            </span>"+
"        </p>"+
"    </fieldset>";
            dates++;
            html = html.replace(/##NUMBER##/g,""+dates);
            var dom=makeDOM(html);
            $("#calendar>div").append(dom);
            return false;
        });

        //add more files
        $("#cr_file_addfield").click(function() {
            var html = "<p>"+
"    <label for='cr_file_##NUMBER##'>Datei hochladen: </label>       "+
"    <input type='file' name='cr_file_##NUMBER##' style='width:400px'/>"+
"    <img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().parent().remove()' />" +
"    </p>";
            files++;
            html = html.replace(/##NUMBER##/g,""+files);
            var dom=makeDOM(html);
            $("#files>div").append(dom);
        return false;
        });
});
  
    
    </script>
</body>
</html>
