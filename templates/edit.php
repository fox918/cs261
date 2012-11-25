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

//TODO verifiy the the user is the assigned one
//TODO list files for download


if(!isset($_REQUEST["order"]))
    die("no order specified");

if(!(isset($_SESSION['user']) && isset($_SESSION['auth'])))
    die("login required");

$user = new user();
if(!$user->authenticate($_SESSION['user'], $_SESSION['auth']))
    die("nice try; real login is required");




$db = new Database();
$orderid = $_REQUEST["order"];
$orderid = $db->escape($orderid);
$ret = $db->run("select * from testing.clients c, testing.jobs j where j.jId = '$orderid' and j.clients_cId = c.cId ");
$row = $ret->fetch_assoc();

if(!isset($row["jId"]))
    die("unknown OrderID");

$name = $row["cName"];
$gender = $row["cGender"];
$phone = $row["cPhone"];
$mobile = $row["cMobile"];
$street = $row["cStreet"];
$city = $row["cCity"];




echo "
<article id=\"edit\">
<form action=\"index.php?page=list\" method=\"post\" accept-charset=\"utf-8\">
    <input type=\"hidden\" name=\"job_id\" value=\"$orderid\" />
    <fieldset>
        <legend>Kunde</legend>
        <fieldset class='floatright'>
            <legend>Adresse</legend> 
            <p class='address'>
            <span>
                <label for=\"job_address\">Strasse</label> 
                <input type=\"text\" name=\"job_address\" id=\"job_address\" value=\"$street\" />
            </span>
            <span>
                <label for=\"job_city\">Ortschaft</label>
                <input type=\"text\" name=\"job_city\" id=\"job_city\" value=\"$city\" />
            </span>
            </p>
        </fieldset>
        <fieldset>
            <legend id='floatleft'>Kontakt</legend>
            <p id=\"contact\">
            <span> <label for=\"job_gender\">Anrede</label>
                <select name=\"job_gender\" id=\"job_gender\">";
switch($gender)
{
    case "m":
        echo "      <option>Frau</option>
                    <option selected='selected'>Herr</option>
                    <option>Firma</option>";
        break;
    
    case "f":
        echo "      <option selected='selected'>Frau</option>
                    <option>Herr</option>
                    <option>Firma</option>";
        break;
    
    default:
        echo "      <option>Frau</option>
                    <option>Herr</option>
                    <option selected='selected'>Firma</option>";
        break;
}
echo "                </select>
            </span>
            <span>
                <label for=\"job_name\">Name</label>
                <input type=\"text\" name=\"job_name\" id=\"job_name\" value=\"$name\" />
            </span>
            <span>
                <label for=\"job_mobile\">Mobile</label>
                <input type=\"text\" name=\"job_mobile\" id=\"job_mobile\" value=\"$mobile\" />
            </span>    
            <span>
                <label for=\"job_phone\">Telefon</label>
                <input type=\"text\" name=\"job_phone\" id=\"job_phone\" value=\"$phone\" />
            </span>
            </p>
        </fieldset>
    </fieldset>";

/*job*/

$title = $row["jName"];
$desc = $row["jDesc"];
$responsible = $row["jResp"];
$creator = $row["Creator_uId"];
$creator = $db->escape($creator);
$ret = $db->run("select uName from users where uId = '$creator'");
$creator = $ret->fetch_assoc()["uName"];

$responsible = $db->escape($creator);
$ret = $db->run("select uName, uId from users");

echo "    <fieldset id=\"order_details\">
        <legend>$title</legend>
        <p>
        <span>
            Erstellt von:
            <span class=\"text\">$creator</span>
        </span>
        <span>
            <label for=\"job_resp\">Verantwortlicher</label>
            <select name=\"job_resp\" id=\"job_resp\">";

while($row = $ret->fetch_assoc())
{
    $rname = $row["uName"];
    if($row["uId"] == $responsible)
    {
        echo "<option selected='selected'>$rname</option>";
    }
    else
    {
        echo "<option>$rname</option>";
    }
}
                
echo "            </select>
        </span>
        </p>
        <fieldset id=\"description\">
            <legend>Beschreibung</legend>
            <textarea name=\"job_descrption\" id=\"job_desc\" rows=\"8\" cols=\"40\">$desc</textarea>
        </fieldset>";
/*material*/

$ret = $db->run("select * from materials where jobs_jId='$orderid'");

echo "        <fieldset id=\"materials\">
            <legend>Material</legend>
            <p>
            <span>Menge</span>
            <span style='left:80px'>Was</span>
            <span style='left:270px'>Notiz</span>
            <span style='left:460px'>Status</span>
            <span style='left:560px'>Lieferdatum</span>
            <span style='left:700px'>Kosten</span>
            </p>
            <div>";
$i = 1;
while($row = $ret->fetch_assoc())
{
    $mname = $row["mName"];
    $note = $row["mDesc"];
    $state = $row["mState"];
    $delDate = $row["mDelDate"];
    $amount = $row["mQuantity"];
    $price = $row["mPrice"];
    $id = $row["mId"];
    
    echo "      <p class=\"material buttoncontainer\" id=\"mat_$i\">
                <input type=\"hidden\" name=\"job_mat_id_$i\" value=\"$id\" />
                <input style='width:70px' type=\"text\" name=\"job_mat_count_$i\" value=\"$amount\"/>
                <input style='left:80px' type=\"text\" name=\"job_mat_title_$i\" value=\"$mname\"/>
                <input style='left:270px' type=\"text\" name=\"job_mat_note_$i\" value=\"$note\"/>
                <select style='left:460px' name=\"job_mat_state_$i\" >";
    switch($state)
    {
        case "order":
            echo "  <option selected=\"selected\">Bestellt</option>
                    <option>Geliefert</option>
                    <option>Benutzt</option>";
            break;
        
        case "arrived":
            echo "  <option>Bestellt</option>
                    <option selected=\"selected\">Geliefert</option>
                    <option>Benutzt</option>";
            break;
        
        default:
            echo "  <option>Bestellt</option>
                    <option>Geliefert</option>
                    <option selected=\"selected\">Benutzt</option>";
            break;
    }
    echo "      </select>
                <input style='left:560px;width:120px' type=\"text\" name=\"job_mat_delivery_$i\" value=\"$delDate\"/>
                <input style='left:700px;width:100px' type=\"text\" name=\"job_mat_price_$i\" value=\"$price\"/>
                <img class=\"closebutton\" src='./img/icons/x_alt_16x16.png' />
                </p>";
    
    $i++;
}

/*notes*/
$i=1;
$ret = $db->run("select * from comText where jobs_jId='$orderid'");
                
 echo "           </div>
            <button id=\"job_mat_addfield\">Hinzufügen</button>
        </fieldset>
        <fieldset id=\"notes\">
            <legend>Notizen</legend>";
  
 while($row = $ret->fetch_assoc())
 {
     $title = $row["coTitle"];
     $text = $row["coText"];
     $id = $row["coTextId"];
     
 
    echo " <div>
                <div class=\"note buttoncontainer\" id=\"note_$i\">
                    <fieldset>
                        <legend>
                            $title
                            <img class=\"closebutton\" src='./img/icons/x_alt_16x16.png' />
                        </legend>
                        <textarea name=\"job_note_$i\" id= rows=\"8\" cols=\"40\">$text</textarea>
                        <input type=\"hidden\" name=\"job_note_id_$i\" value=\"$id\" />

                    </fieldset>
                </div>
            </div>";
}
            
  echo "        <button id=\"job_note_addfield\">Hinzufügen</button>
        </fieldset>
    </fieldset>

    <fieldset id=\"calendar\">
        <legend>Kalender</legend>
        <div>";
$i=1;
$ret = $db->run("select * from shedule where jobs_jId='$orderid'");
while($row = $ret->fetch_assoc())
{
    $start = $row["sStart"];
    $stop = $row["sStop"];
    $note = $row["sComment"];
    $id = $row["sId"];
    
    

echo "          <fieldset  id=\"date_$i\" class=\"date buttoncontainer\">
                <input type=\"hidden\" name=\"job_date_id_$i\" value=\"$id\" />
                <legend>
                    $start
                    <img class=\"closebutton\" src='./img/icons/x_alt_16x16.png' />
                </legend>
                <p>
                <span>
                    <label for=\"job_date_statime_$i\">Startzeit</label>
                    <input type=\"text\" name=\"job_date_statime_1\" id=\"job_date_statime_1\" value=\"$start\"/>
                </span>
                <span>
                    <label for=\"job_date_stotime_$i\">bis um</label>
                    <input type=\"text\" name=\"job_date_stotime_1\" id=\"job_date_stotime_1\" value=\"$stop\"/>
                </span>
                <span>
                    <label for=\"job_date_desc_$i\">Notiz</label>
                    <input type=\"text\" name=\"job_date_desc_1\" id=\"job_date_desc_1\" style=\"width:300px\" value=\"$note\"/>
                </span>
                </p>
            </fieldset>";

}
            
echo "  </div>
        <button id=\"job_date_addfield\">Hinzufügen</button>
    </fieldset>

    <fieldset id=\"files\">
        <legend>angehängte Dateien</legend>
        <div>
            <p>
            <label for=\"job_file_1\">Datei hochladen: </label>
            <input type=\"file\" name=\"job_file_1\" style=\"width:400px\"/>
            </p>
        </div>
        <button id=\"job_file_addfield\">Datei hinzufügen</button>
    </fieldset>
    <div class=\"control\">
        <div>
            <button id=\"save\">Auftrag speichern</button>
            <button id=\"publish\">Auftrag veröffentlichen</button>
            <button id=\"delete\">Auftrag löschen</button>
        </div>
        <div>
            <button id=\"billing\">Auftrag wird abgerechnet</button>
            <button id=\"archive\">Auftrag ins Archiv verschieben</button>
        </div>
    </div>
</form>
</article>";




?>