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
     <form action="./index.php" method="get" accept-charset="utf-8">
         <div class='sort'>
             <p>Sortiere nach</p>
             <input type="hidden" name="page" value="list" />
             <select name="sortby">
                 <option value="name">Name</option>
                 <option value="city">Ortschaft</option>
                 <option value="title">Auftragstitel</option>
                 <option value="number">Auftragsnummer</option>
                 <option value="creation">Erstellungsdatum</option>
                 <option value="change">Änderungsdatum</option>
             </select>
         </div>
         <div class='search'>
             <p> Suche nach <input type="text" name="search" value="" /></p>
             <button type="submit">Los!</button>
         </div>
     </form>
 </div>
 <ul id="joblist">
     <li id="1233" class="job">
     <p>
     <span class='client'>Hans Müller</span>
     <span class='street'>StrassenStrasse 12</span>
     <span class='location'>Ortschaft</span>
     <span class='title'>Auftragstitel</span>
     <span class='id'>1233</span>
     </p>
     </li>
     <li>
     <p id="1234" class="job">
     <span class='client'>Hans PETER AG</span>
     <span class='street'>kurze 12</span>
     <span class='location'>langlanglang</span>
     <span class='title'>Auftragstitel lang lang lang</span>
     <span class='id'>1233</span>
     </p>
     </li>
     <li id="1235" class="job">
     <p>
     <span class='client'>Hans Müller</span>
     <span class='street'>senStrasse 12</span>
     <span class='location'>Ortschaft</span>
     <span class='title'>Aasfstitel</span>
     <span class='id'>1233</span>
     </p>
     </li>
 </ul>
 </article>
