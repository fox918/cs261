<?php 
//die if not called by ../classes.php
if(!isset($check)){echo 'and now for something completely different';die;}
?>

<article id="login">
<fieldset>
    <legend>Bitte melden Sie sich an</legend>
    <form action="./index.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="post" accept-charset="utf-8">
        <div>
            <p>
            <label for="login_username">Benutzername</label>
            <input type="text" name="login_username" id="login_username" value="" />
            </p>
            <p>
            <label for="login_password">Passwort</label>
            <input type="password" name="login_password" id="login_password" value="" />
            </p>
            <input type="submit" value="Anmelden" />
        </div>
    </form>
</fieldset>
</article>
