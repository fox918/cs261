<?php 
//die if not called by ../classes.php
if(!defined('ACCESS'))
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
<article id="admin">
    <fieldset>
        <legend>Benutzer</legend>
<fieldset id="list">
<legend>Benutzerliste</legend>
    
            <ul>
                <li><p><span>Benutzername</span> <button type="submit">Passwort zurücksetzen</button></p> <img src="img/icons/x_alt_16x16.png" alt="" /></li>
                <li><p><span>hans</span> <button type="submit">Passwort zurücksetzen</button></p> <img src="img/icons/x_alt_16x16.png" alt="" /></li>
                <li><p><span>peter</span> <button type="submit">Passwort zurücksetzen</button></p> <img src="img/icons/x_alt_16x16.png" alt="" /></li>
            </ul>

</fieldset>
        <fieldset id="newUser">
            <legend>Benutzer erstellen</legend>
        <p>
        <label for="new_name">Benutzername</label>
        <input type="text" name="new_name" id="new_name" value="" />
        <label for="new_pw">Passwort</label>
        <input type="password" name="new_pw" id="new_pw" value="" />    
        <label for="new_type"> Abteilung</label>
<select name="new_type" id="new_type">
<option value="admin">Verwaltung</option>
<option value="worker">Arbeiter</option>
<option value="store">Lager</option>
</select>
<button type="submit">Erstellen</button>
        </p>
        </fieldset>
    </fieldset>
<fieldset>
    <legend>Einstellungen</legend>
    <p>
    <label for="pw">Mein Passwort ändern zu:</label>
    <input type="password" name="pw" id="pw" value="" />
    <button type="submit">ändern</button>    
</p>
    
</fieldset>
</article>
