<?php

/* CONFIG FILE
 *
 * Hello and welcome
 * this is the config file for this website.
 *
 */


/* INSTALL 
 *
 * This flag toggles the install mode, set it to 0 once
 * you have set up the database
 *
 * 0    Installation completed
 * 1    Installation not completed, db not set up yet.
 */

define('INSTALL', 1);

/* DATABASE
 *
 * Enter the connection data for the database here
 */

define('DB_USER', 'root');                //db user
define('DB_PASSWORD', 'IamSoSecure');     //db password

define('DB_HOST', 'localhost');           //db host, usually localhost
define('DB_DATABASE', 'testing');        //which database on the host

/* DEBUG
 *
 * Some options to debug
 */

define('DEBUG', 1);                    //DANGER DANGER HIGH VOLTAGE

