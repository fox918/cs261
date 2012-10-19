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

echo 'Erstelle Datenbankschema';
//TODO create DB



echo 'Fülle Datenbank mit Daten';


echo 'Datenbank Erstellung abgeschlossen, Sie können das Flag INSTALL in der config.php auf 0 setzen.';
