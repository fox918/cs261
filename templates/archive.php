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
             <input type="hidden" name="page" value="archive" />
             <select name="sortby">
                 <option value="name">Name</option>
                 <option value="city">Ortschaft</option>
                 <option value="title">Auftragstitel</option>
                 <option value="number">Auftragsnummer</option>
             </select>
         </div>
         <div class='search'>
             <p> Suche nach <input type="text" name="search" value="" /></p>
             <button type="submit">Los!</button>
         </div>
     </form>
    </div>
    <ul id="joblist">
<?php
require_once 'db.php';

if(isset($_GET["sortby"]) && isset($_GET["search"]))
{
    $param = $_GET["sortby"];
    $db = new Database();
    $search = $db->escape($_GET["search"]);
    
    
    $sort = "cName";
    switch($param)
    {
        case "city":
            $sort = "cCity";
            break;
        
        case "title":
            $sort = "jName";
            break;
        
        case "number":
            $sort = "jId";
            break;
    }
    
    $user = new user();
    $user->authenticate($_SESSION['user'], $_SESSION['auth']);
    $id = $user->getId();
    $ret = $db->run("select cName, cStreet, cCity, jName, jId from clients c, jobs j where ((cName like '%$search%' or  cStreet like '%$search%' or cCity like '%$search%' or jName like '%$search%' or  jId like '%$search%') and c.cId = j.clients_cId and j.jResp = '$id' and j.jStage = 'finished') order by $sort asc");
     while($row = $ret->fetch_assoc())
     {
         $id = $row["jId"];
         $name = $row["cName"];
         $location = $row["cCity"];
         $street = $row["cStreet"];
         $title = $row["jName"];
         echo " <li id=\"$id\" class=\"job\">
                <p>
                <span class='client'>$name</span>
                <span class='street'>$street</span>
                <span class='location'>$location</span>
                <span class='title'>$title</span>
                <span class='id'>$id</span>
                </p>
                </li>";
     }
}
else
{
    $user = new user();
    $user->authenticate($_SESSION['user'], $_SESSION['auth']);
    $id = $user->getId();
    
    $db = new Database();
    $ret = $db->run("select cName, cStreet, cCity, jName, jId from clients c, jobs j where (c.cId = j.clients_cId and j.jResp = '$id' and j.jStage = 'finished')");
    
    while($row = $ret->fetch_assoc())
    {
        $id = $row["jId"];
        $name = $row["cName"];
        $location = $row["cCity"];
        $street = $row["cStreet"];
        $title = $row["jName"];
        echo " <li id=\"$id\" class=\"job\">
               <p>
               <span class='client'>$name</span>
               <span class='street'>$street</span>
               <span class='location'>$location</span>
               <span class='title'>$title</span>
               <span class='id'>$id</span>
               </p>
               </li>";
    }
}
?>
        
        
    </ul>
</article>
