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
* create page
*/
?>
<article id="create">
<fieldset>
    <legend>Kunde</legend>
    <fieldset class='floatright'>
        <legend>Adresse</legend> 
        <p class='address'>
        <span>Strasse <input type="text" name="" id="" value="" /></span>
        <span>Ortschaft <input type="text" name="" id="" value="" /></span>
        </p>
    </fieldset>
    <fieldset>
        <legend id='floatleft'>Kontakt</legend>
        <p id="contact">
        <span>Anrede 
            <select name="" id="">
                <option>Frau</option>
                <option>Herr</option>
                <option>Firma</option>
            </select>
        </span>
        <span>Mobile <input type="text" name="" id="" value="" /></span>    
        <span>Telefon <input type="text" name="" id="" value="" /></span>
        </p>
    </fieldset>
</fieldset>

<fieldset id="order_details">
    <legend>Auftrag</legend>
    <p>
    <span>Auftragstitel <input type="text" name="" id="" value="" /></span>
    <span>Verantwortlicher 
        <select name="" id="">
            <option>Hans</option>
            <option>Muster</option>
            <option>Petrus</option>
        </select>
    </span>
    </p>
    <fieldset>
        <legend>Beschreibung</legend>
        <textarea name="" id="" rows="15"></textarea>
    </fieldset>
    <fieldset id="materials">
        <legend>Material</legend>
        <ul>
            <li class="material"><span class='amount'>4</span><span class='description'>Fenster</span><span class='price'>34.23 Fr.</span></li>
        </ul>
        <p>
        <span>Menge</span>
        <span style='left:80px'>Was</span>
        <span style='left:270px'>Notiz</span>
        <span style='left:460px'>Status</span>
        <span style='left:560px'>Lieferdatum</span>
        <span style='left:700px'>Kosten</span>
        </p>
        <p>
        <input style='width:70px' type="text" name="" id="" value="" />
        <input style='left:80px' type="text" name="" id="" value="" />
        <input style='left:270px' type="text" name="" id="" value="" />
        <select style='left:460px' name="" id="">
            <option>Bestellt</option>
            <option>Geliefert</option>
            <option>Benutzt</option>    
        </select>
        <input style='left:560px;width:120px' type="text" name="" id="" value="" />
        <input style='left:700px;width:100px' type="text" name="" id="" value="" />
        </p>
        <input type="button" name="" id="" value="Hinzufügen" />
    </fieldset>
    <fieldset>
        <legend>Notizen</legend>
        <div>
            <p>
            <span>Titel<input type="text" name="" id="" value="" /></span>
            </p>
            <textarea name="" id="" rows="13" cols="40"></textarea>
        </div>
        <input type="button" name="" id="" value="Hinzufügen" />
    </fieldset>
</fieldset>

<fieldset>
    <legend>Kalender</legend>
    <fieldset>
        <legend>12.23.45</legend>
        input here
    </fieldset>
    <p><span>Datum</span><span>Zeit</span> - <span>Datum</span><span>Zeit</span> <span>Beschreibung</span></p>
    <input type="text" name="" id="" value="DATUM" />
    <input type="text" name="" id="" value="Startzeit" />
    <input type="text" name="" id="" value="Datum" />
    <input type="text" name="" id="" value="Beschreibung" />
    <button type="submit">Termin hinzufuegen</button>
</fieldset>

<fieldset>
    <legend>angehängte Dateien</legend>
    <div>
        <p><span>hanspeter.doc</span></p>
    </div>
    <input type="text" name="" id="" value="FILE" />
    <button type="submit">Datei hinzufuegen</button>
</fieldset>
<div class="control">
    <button type="submit">Erstellen</button>
</div>
</article>

<aside>

</aside>


