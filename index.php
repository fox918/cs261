<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="title" content="">
    <meta name="description" content="">
    <meta name="author" content="Your Name Here">

    <!-- IE and chrome fix -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!-- Dublin Core Metadata : http://dublincore.org/ -->
    <meta name="DC.title" content="Project Name">
    <meta name="DC.subject" content="What you're about.">
    <meta name="DC.creator" content="Who made this site.">

    <!-- Favicon -->
    <link rel="shortcut icon" href="./img/favicon.ico">
    <!-- Humans -->
    <link rel="humans" href="./humans.txt">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="./css/style.css">

    <title></title>
</head>

<body>

    <div id="content"> 
        <header>
        <div>
            <div id="logo">
                <img src="./img/logo.png" alt="logo" />
                <h1>Auftragverwaltung</h1>
            </div>
            <div id="logininfo">
                <p id="username">Hans Muster</p>
                <p id="department">Verwaltung</p>
                <form action="./index.php?action=logout" method="post">
                    <input type="submit" value="Ausloggen" />
                </form>
            </div>
        </div>
        <div style="clear:both"></div>
        <nav>
        <ul>
            <li><a class="active" href="index.php?p=create">Erfassung</a></li>
            <li><a href="index.php?p=list">Aufträge</a></li>
            <li><a href="index.php?p=archive">Archiv</a></li>
            <li><a href="index.php?p=admin">Verwaltung</a></li>
        </ul>
        </nav>
        </header>

        <article id="create">
        <fieldset>
            <legend>Kunde</legend>
            asfasf
        </fieldset>
        <fieldset>
            <legend>Auftrag</legend>

        </fieldset>
        <fieldset>
            <legend>Kalender</legend>
        </fieldset>
        <fieldset>
            <legend>angehängte Dateien</legend>
        </fieldset>
        <div class="control">
            <button type="submit">Erstellen</button>
        </div>
        </article>

        <aside>

        </aside>

        <footer>

        </footer>

    </div>

    <!-- Javascript -->
    <script src='./js/jquery-1.8.2.min.js'></script>
    <script src="./js/main.js"></script>

</body>
</html>
