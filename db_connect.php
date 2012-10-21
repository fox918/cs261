<?php
require_once 'config.php';
mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);  
@mysql_select_db(DB_DATABASE) or die( "Unable to select database");
mysql_query( "SET NAMES 'utf8'" );
