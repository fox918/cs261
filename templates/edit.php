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
* edit page
*/

?>

<article id="edit">
<form action="index.php?page=list" method="post" accept-charset="utf-8">
    <fieldset>
        <legend>Kunde</legend>
        <fieldset class='floatright'>
            <legend>Adresse</legend> 
            <p class='address'>
            <span>
                <label for="cr_address">Strasse</label> 
                <input type="text" name="cr_address" id="cr_address" value="Musterstrasse 23" />
            </span>
            <span>
                <label for="cr_city">Ortschaft</label>
                <input type="text" name="cr_city" id="cr_city" value="Leets" />
            </span>
            </p>
        </fieldset>
        <fieldset>
            <legend id='floatleft'>Kontakt</legend>
            <p id="contact">
            <span> <label for="cr_gender">Anrede</label>
                <select name="cr_gender" id="cr_gender">
                    <option>Frau</option>
                    <option selected='selected'>Herr</option>
                    <option>Firma</option>
                </select>
            </span>
            <span>
                <label for="cr_name">Name</label>
                <input type="text" name="cr_name" id="cr_name" value="Hanspeter Muster" />
            </span>
            <span>
                <label for="cr_mobile">Mobile</label>
                <input type="text" name="cr_mobile" id="cr_mobile" value="012 234 56 78" />
            </span>    
            <span>
                <label for="cr_phone">Telefon</label>
                <input type="text" name="cr_phone" id="cr_phone" value="987 987 65 32" />
            </span>
            </p>
        </fieldset>
    </fieldset>

    <fieldset id="order_details">
        <legend>Renovation des Dachstuhls</legend>
        <p>
        <span>
            Erstellt von:
            <span class="text">Max Muster</span>
        </span>
        <span>
            <label for="cr_resp">Verantwortlicher</label>
            <select name="cr_resp" id="cr_resp">
                <?php
                //TODO implement DB to get all users
                ?>
                <option value="userid" selected='selected'>Hans</option>
                <option >Muster</option>
                <option>Petrus</option>
            </select>
        </span>
        </p>
        <fieldset>
            <legend>Beschreibung</legend>
            <p class="text">
                hallo this is a descriptopn
            </p>
            <button>ändern</button>
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
                <input style='width:70px' type="text" name="cr_mat_count_1" value="32"/>
                <input style='left:80px' type="text" name="cr_mat_title_1" value="herpderp"/>
                <input style='left:270px' type="text" name="cr_mat_note_1" value="herpderp"/>
                <select style='left:460px' name="cr_mat_state_1">
                    <option>Bestellt</option>
                    <option selected="selected">Geliefert</option>
                    <option>Benutzt</option>    
                </select>
                <input style='left:560px;width:120px' type="text" name="cr_mat_delivery_1" value="2.2.2012"/>
                <input style='left:700px;width:100px' type="text" name="cr_mat_price_1" value="34.-"/>
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
                            I am a note
                        </legend>
                        <p class="text">
                           Hans peter text note
                        </p>
                        <button>ändern</button>
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
                  Do, 25.09.2012 
                </legend>
                <p>
                <span>
                    <label for="cr_date_statime_1">Startzeit</label>
                    <input type="text" name="cr_date_statime_1" id="cr_date_statime_1" value="09:00"/>
                </span>
                <span>
                    <label for="cr_date_stotime_1">bis um</label>
                    <input type="text" name="cr_date_stotime_1" id="cr_date_stotime_1" value="09:30"/>
                </span>
                <span>
                    <label for="cr_date_desc_1">Notiz</label>
                    <input type="text" name="cr_date_desc_1" id="cr_date_desc_1" style="width:300px" value="FOOBAR HERPDERP"/>
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
    <div class="control">
        <button>Auftrag speichern</button>
        <button>Auftrag veröffentlichen</button>
        <button>Auftrag ist abgerechnet</button>
        <button>Auftrag ins Archiv verschieben</button>
    </div>
</form>
</article>
