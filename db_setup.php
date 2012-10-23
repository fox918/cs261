<?php

//get the DB login data
require_once 'config.php';

//check if INSTALL flag == 1
if(INSTALL!=1){ 
    $errorTitle='Konnte DB nicht initialisieren';
    $error='Die Konstante INSTALL ist nicht richtig gesetzt, überprüfe die config.php.';
    include './templates/error.php';
die;
}

function processFile($path){
    $sqlScript = file_get_contents($path);

    //strip unnecessary things
    $sqlScript = preg_replace ("%/\*(.*)\*/%Us", '', $sqlScript);
    $sqlScript = preg_replace ("%^--(.*)\n%mU", '', $sqlScript);
    $sqlScript = preg_replace ("%^$\n%mU", '', $sqlScript);
    $sqlScript = preg_replace ("/ordermgmt/", DB_DATABASE, $sqlScript);

    //open connection
    include 'db_connect.php';

    //escaping
    mysql_real_escape_string($sqlScript);
    $queries = explode(";", $sqlScript);

    $success = true;
    foreach ($queries as $query){
        if ($query != '' && $query != ' '){
            $success=$success&&mysql_query($query);
        }
    } 
    if($success){
        echo "Erfolg \n";
    }
}
echo "Erstelle Datenbankschema \n";
processFile('./installfiles/db_create_script.sql');

echo "Fülle Datenbank mit Daten\n";
processFile('./installfiles/db_insert_script.sql');

echo "Datenbank Erstellung abgeschlossen, Sie können das Flag INSTALL in der config.php auf 0 setzen. \n";
