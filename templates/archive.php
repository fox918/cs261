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
 * list all order page
 */

?>
<article id='list'>
    <div class='sortsearch'>
        <div class='sort'>
        <p>Sortiere nach</p>
        <select>
            <option>Name</option>
            <option>Ortschaft</option>
            <option>Auftragstitel</option>
            <option>Auftragsnummer</option>
            <option>Erstellungsdatum</option>
            <option>Änderungsdatum</option>
        </select>
        </div>
        <div class='search'>
            <p> Suche nach <input type="text" name="" id="" value="" /></p>
            <button type="submit">Los!</button>
        </div>
    </div>
    <ul>
        <li>
        <p>
        <span class='client'>Hans Müller</span>
        <span class='street'>StrassenStrasse 12</span>
        <span class='location'>Ortschaft</span>
        <span class='title'>Auftragstitel</span>
        <span class='id'>1233</span>
        </p>
        <div>
            TOOLS
        </div>
        </li>
        <li>
        <p>
        <span class='client'>Hans PETER AG</span>
        <span class='street'>kurze 12</span>
        <span class='location'>langlanglang</span>
        <span class='title'>Auftragstitel lang lang lang</span>
        <span class='id'>1233</span>
        </p>
        <div>
            TOOLS
        </div>
        </li>
        <li>
        <p>
        <span class='client'>Hans Müller</span>
        <span class='street'>senStrasse 12</span>
        <span class='location'>Ortschaft</span>
        <span class='title'>Aasfstitel</span>
        <span class='id'>1233</span>
        </p>
        <div>
            TOOLS
        </div>
        </li>
    </ul>
</article>
