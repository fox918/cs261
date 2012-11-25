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
    <input type="hidden" name="job_id" value="1337" />
    <fieldset>
        <legend>Kunde</legend>
        <fieldset class='floatright'>
            <legend>Adresse</legend> 
            <p class='address'>
            <span>
                <label for="job_address">Strasse</label> 
                <input type="text" name="job_address" id="job_address" value="Musterstrasse 23" />
            </span>
            <span>
                <label for="job_city">Ortschaft</label>
                <input type="text" name="job_city" id="job_city" value="Leets" />
            </span>
            </p>
        </fieldset>
        <fieldset>
            <legend id='floatleft'>Kontakt</legend>
            <p id="contact">
            <span> <label for="job_gender">Anrede</label>
                <select name="job_gender" id="job_gender">
                    <option>Frau</option>
                    <option selected='selected'>Herr</option>
                    <option>Firma</option>
                </select>
            </span>
            <span>
                <label for="job_name">Name</label>
                <input type="text" name="job_name" id="job_name" value="Hanspeter Muster" />
            </span>
            <span>
                <label for="job_mobile">Mobile</label>
                <input type="text" name="job_mobile" id="job_mobile" value="012 234 56 78" />
            </span>    
            <span>
                <label for="job_phone">Telefon</label>
                <input type="text" name="job_phone" id="job_phone" value="987 987 65 32" />
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
            <label for="job_resp">Verantwortlicher</label>
            <select name="job_resp" id="job_resp">
                <?php
                //TODO implement DB to get all users
                ?>
                <option value="userid" selected='selected'>Hans</option>
                <option >Muster</option>
                <option>Petrus</option>
            </select>
        </span>
        </p>
        <fieldset id="description">
            <legend>Beschreibung</legend>
            <textarea name="job_descrption" id="job_desc" rows="8" cols="40">hans</textarea>
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
                <p class="material buttoncontainer" id="mat_1">
                <input style='width:70px' type="text" name="job_mat_count_1" value="32"/>
                <input style='left:80px' type="text" name="job_mat_title_1" value="herpderp"/>
                <input style='left:270px' type="text" name="job_mat_note_1" value="herpderp"/>
                <select style='left:460px' name="job_mat_state_1" >
                    <option>Bestellt</option>
                    <option selected="selected">Geliefert</option>
                    <option>Benutzt</option>    
                </select>
                <input style='left:560px;width:120px' type="text" name="job_mat_delivery_1" value="2.2.2012"/>
                <input style='left:700px;width:100px' type="text" name="job_mat_price_1" value="34.-"/>
                <img class="closebutton" src='./img/icons/x_alt_16x16.png' />
                </p>
            </div>
            <button id="job_mat_addfield">Hinzufügen</button>
        </fieldset>
        <fieldset id="notes">
            <legend>Notizen</legend>
            <div>
                <div class="note buttoncontainer" id="note_1">
                    <fieldset>
                        <legend>
                            I am a note
                            <img class="closebutton" src='./img/icons/x_alt_16x16.png' />
                        </legend>
                            <textarea name="job_note_1" id= rows="8" cols="40"></textarea>
                        <button>ändern</button>
                    </fieldset>
                </div>
            </div>
            <button id="job_note_addfield">Hinzufügen</button>
        </fieldset>
    </fieldset>

    <fieldset id="calendar">
        <legend>Kalender</legend>
        <div>
            <fieldset  id="date_1" class="date buttoncontainer">
                <legend>
                    Do, 25.09.2012 
                    <input type=hiddene" name="job_date_id_1" value="1234" />
                    <img class="closebutton" src='./img/icons/x_alt_16x16.png' />
                </legend>
                <p>
                <span>
                    <label for="job_date_statime_1">Startzeit</label>
                    <input type="text" name="job_date_statime_1" id="job_date_statime_1" value="09:00"/>
                </span>
                <span>
                    <label for="job_date_stotime_1">bis um</label>
                    <input type="text" name="job_date_stotime_1" id="job_date_stotime_1" value="09:30"/>
                </span>
                <span>
                    <label for="job_date_desc_1">Notiz</label>
                    <input type="text" name="job_date_desc_1" id="job_date_desc_1" style="width:300px" value="FOOBAR HERPDERP"/>
                </span>
                </p>
            </fieldset>
        </div>
        <button id="job_date_addfield">Hinzufügen</button>
    </fieldset>

    <fieldset id="files">
        <legend>angehängte Dateien</legend>
        <div>
            <p>
            <label for="job_file_1">Datei hochladen: </label>
            <input type="file" name="job_file_1" style="width:400px"/>
            </p>
        </div>
        <button id="job_file_addfield">Datei hinzufügen</button>
    </fieldset>
    <div class="control">
        <div>
            <button id="save">Auftrag speichern</button> <br />
            <button id="publish">Auftrag veröffentlichen</button>
        </div>
        <div>
            <button id="billing">Auftrag wird abgerechnet</button> <br />
            <button id="archive">Auftrag ins Archiv verschieben</button>
        </div>
    </div>
</form>
</article>
