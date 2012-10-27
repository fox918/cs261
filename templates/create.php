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
* create page
*/
?>
<article id="create">
<form action="index.php?page=list" method="post" accept-charset="utf-8">
    <fieldset>
    <legend>Kunde</legend>
    <fieldset class='floatright'>
        <legend>Adresse</legend> 
        <p class='address'>
        <span>
            <label for="cr_address">Strasse</label> 
            <input type="text" name="cr_address" id="cr_address" value="" />
        </span>
        <span>
            <label for="cr_city">Ortschaft</label>
            <input type="text" name="cr_city" id="cr_city" value="" />
        </span>
        </p>
    </fieldset>
    <fieldset>
        <legend id='floatleft'>Kontakt</legend>
        <p id="contact">
        <span> <label for="cr_gender">Anrede</label>
            <select name="cr_gender" id="cr_gender">
                <option>Frau</option>
                <option>Herr</option>
                <option>Firma</option>
            </select>
        </span>
        <span>
            <label for="cr_name">Name</label>
            <input type="text" name="cr_name" id="cr_name" value="" />
        </span>
        <span>
            <label for="cr_mobile">Mobile</label>
            <input type="text" name="cr_mobile" id="cr_mobile" value="" />
        </span>    
        <span>
            <label for="cr_phone">Telefon</label>
            <input type="text" name="cr_phone" id="cr_phone" value="" />
        </span>
        </p>
    </fieldset>
</fieldset>

<fieldset id="order_details">
    <legend>Auftrag</legend>
    <p>
    <span>
        <label for="cr_title">Auftragstitel</label>         
        <input type="text" name="cr_title" id="cr_title" value="" />
    </span>
    <span>
        <label for="cr_resp">Verantwortlicher</label>
        <select name="cr_resp" id="cr_resp">
<?php
//TODO implement DB to get all users
?>
            <option value="userid" >Hans</option>
            <option>Muster</option>
            <option>Petrus</option>
        </select>
    </span>
    </p>
    <fieldset>
        <legend>Beschreibung</legend>
        <textarea name="cr_desc" id="" rows="15"></textarea>
    </fieldset>
    <fieldset id="materials">
        <legend>Material</legend>
        <p>
            <span>Menge</span>
            <span style='left:80px'>Was</span>
            <span style='left:270px'>Notiz</span>
            <span style='left:460px'>Status</span>
            <span style='left:560px'>Lieferdatum</span>
            <span style='left:700px'>Kosten</span>
        </p>
        <div>
        <p class="material" id="mat_1">
            <input style='width:70px' type="text" name="cr_mat_count_1" />
            <input style='left:80px' type="text" name="cr_mat_title_1" />
            <input style='left:270px' type="text" name="cr_mat_note_1" />
            <select style='left:460px' name="cr_mat_state_1">
                <option>Bestellt</option>
                <option>Geliefert</option>
                <option>Benutzt</option>    
            </select>
            <input style='left:560px;width:120px' type="text" name="cr_mat_delivery_1" />
            <input style='left:700px;width:100px' type="text" name="cr_mat_price_1"/>
        </p>
        </div>
            <button id="cr_mat_addfield">Hinzufügen</button>
    </fieldset>
    <fieldset id="notes">
        <legend>Notizen</legend>
        <div>
            <div class="note" id="note_1">
            <fieldset>
                <legend>
                    <input type="text" name="cr_note_title_1" />
                </legend>
                <textarea name="cr_note_1" id="" rows="13" cols="40"></textarea>
            </fieldset>
            </div>
        </div>
         <button id="cr_note_addfield">Hinzufügen</button>
    </fieldset>
</fieldset>

<fieldset id="calendar">
    <legend>Kalender</legend>
        <div>
        <fieldset  id="date_1" class="date">
            <legend>
                Datum <input type="text" name="cr_date_1"/>
            </legend>
           <p>
        <span>
            <label for="cr_date_statime_1">Startzeit</label>
            <input type="text" name="cr_date_statime_1" id="cr_date_statime_1"/>
        </span>
        <span>
            <label for="cr_date_stotime_1">bis um</label>
            <input type="text" name="cr_date_stotime_1" id="cr_date_stotime_1"/>
        </span>
        <span>
            <label for="cr_date_desc_1">Notiz</label>
            <input type="text" name="cr_date_desc_1" id="cr_date_desc_1"/>
        </span>
        </p>
    </fieldset>
    </div>
    <button id="cr_date_addfield">Hinzufügen</button>
</fieldset>

<fieldset id="files">
    <legend>angehängte Dateien</legend>
    <div>
    <p>
    <label for="cr_file_1">Datei hochladen: </label>
    <input type="file" name="cr_file_1" style="width:400px"/>
    </p>
    </div>
        <button id="cr_file_addfield">Datei hinzufügen</button>
</fieldset>

<input type="hidden" name="cr_mat_counter" id="cr_mat_counter" value="1" />
<input type="hidden" name="cr_note_counter" id="cr_note_counter" value="1" />
<input type="hidden" name="cr_date_counter" id="cr_date_counter" value="1" />
<input type="hidden" name="cr_file_counter" id="cr_file_counter" value="1" />
<div class="control">
    <input type="submit" value="Auftrag erstellen" />
</div>
</form>
</article>

