<?php 
//die if not called by ../classes.php
if(!isset($check))
{
    $errorTitle='Unerlaubter Zugriff';
    $error='Dieser Zugriff ist nicht erlaubt';
    include 'error.php';
    die;
}
/*
 * Administration page
 */

?>
<article>
    <fieldset>
        <legend>Benutzer</legend>
            <ul>
                <li>ROOT</li>
                <li>HANS</li>
            </ul>
    </fieldset>
<fieldset>
    <legend>Einstellungen</legend>
    <p>BLABLABLABLA</p>
</fieldset>
</article>
