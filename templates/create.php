<?php 
//die if not called by ../classes.php
if(!isset($check)){echo 'and now for something completely different';die;}

/*
 * create page
 */
?>
<article id="create">
<fieldset>
    <legend>Kunde</legend>
     <fieldset>
        <legend>Adresse</legend>

    </fieldset>
    <fieldset>
        <legend>Notizen</legend>

    </fieldset>
</fieldset>

<fieldset>
    <legend>Auftrag</legend>
    <fieldset>
        <legend>auszufuehrnde Arbeiten</legend>
        <p>arbeit blablablalba</p>
        <input type="text" name="" id="" value="Arbeit" />
        <input type="text" name="" id="" value="Zeit" />
        <input type="button" name="" id="" value="Hinzufuegen" />
    </fieldset>
    <fieldset>
        <legend>Material</legend>
        <p>Material blablabla</p>
        <input type="text" name="" id="" value="Menge" />
        <input type="text" name="" id="" value="Was" />
        <input type="text" name="" id="" value="Kosten" />
        <input type="button" name="" id="" value="Hinzufuegen" />
    </fieldset>
    <fieldset>
        <legend>Notizen</legend>
        <input type="text" name="" id="" cols="3" value="Notizen" />
    </fieldset>
</fieldset>

<fieldset>
    <legend>Kalender</legend>
    <fieldset>
        <legend>12.23.45</legend>
        input here
    </fieldset>
    <input type="text" name="" id="" value="DATUM" />
    <button type="submit">Termin hinzufuegen</button>
</fieldset>

<fieldset>
    <legend>angehängte Dateien</legend>
    <input type="text" name="" id="" value="FILE" />
    <button type="submit">Datei hinzufuegen</button>
</fieldset>
<div class="control">
    <button type="submit">Erstellen</button>
</div>
</article>

<aside>

</aside>


