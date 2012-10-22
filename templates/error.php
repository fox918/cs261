<?php
    if(!isset($check)){
        echo '<!doctype html><html><head><meta charset="utf-8">
            <link rel="stylesheet" href="./css/style.css"><title>Fehler</title>
            </head><body>';
    }

?>
<article class='errorpage'>
<fieldset class='error'>
    <legend>
<?php 
if(isset($errorTitle))
{
    echo $errorTitle;
}
else{
    echo "Es ist ein Fehler aufgetreten";
}
?>
    </legend>
    <p> 
<?php 
if(isset($error))
{
    echo $error;            
}elseif(isset($_GET['error'])){
    echo $_GET['error'];
}
?> 
    </p>
</fieldset>
<fieldset class='quote'>
<legend>Random Quote</legend>
    <p>
<?php
$quotes = array(
    "Das Schlimmste ist nicht: Fehler haben, nicht einmal, sie nicht bekämpfen, ist schlimm. Schlimm ist, sie zu verstecken.",
    "Man fällt nicht über seine Fehler. Man fällt immer über seine Feinde, die diese Fehler ausnutzen. ",
    "Die eigenen Fehler entdecken wir zuerst bei anderen. ",
    "The problem with troubleshooting is that trouble shoots back.  ",
    "User, n.  The word computer professionals use when they mean idiot.",
    "Computers are like Old Testament gods; lots of rules and no mercy.",
    "A computer lets you make more mistakes faster than any invention in human history - with the possible exceptions of handguns and tequila.",
    "There are three kinds of death in this world.  There's heart death, there's brain death, and there's being off the network."
);

echo $quotes[array_rand($quotes,1)];

?>
    </p>
</fieldset>    
</article>
<?php
if(!isset($check)){
    echo '</body></html>';
}

