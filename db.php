<?php

require_once 'config.php';

/* Database class
 *
 * Handles all the Data transfers between the page/php and the database
 */

date_default_timezone_set('Europe/Zurich');

class Database
{
    private $db;
    
    function __construct() {
        $this->db = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
    }


    /*Runs the given SQL staement and returns the result*/
    public function run($statement)
    {
        if($this->db->connect_errno > 0)
        {
            die("Error: Could not query database: $this->db->error");
        }
        $ret = $this->db->query($statement);
        
        //TODO remove
//        $err = $this->db->error;
//        echo "err: '$err'  statement: '$statement' <br>";
        
        return  $ret;
    }
    
    /*returns the escaped string*/
    public function escape($string)
    {
        return $this->db->real_escape_string($string);
    }


}


class Validate
{
   
    
    /* $type is the type (the name in the post basically)
     * $text is it's contents
     * 
     * $sanitized is the sanitized string (not escaped!)
     * $error is the error that was encountered (if there was one)
     * returns a true if valid
     */
    public function check($type, $text, &$sanitized, &$error)
    {
        /*removing all numbers in $type*/
        
        $type_ = preg_replace('/[0-9]*/', '', $type);
        
        switch ($type_) {
            /*alphanumeric*/
            case "cr_name":
            case "cr_city":
            case "cr_title":
            case "cr_address":
            case "cr_mat_title_":
            case "cr_date_desc_":
            case "cr_mat_note_":
            case "cr_file_":
                $return = filter_var($text, FILTER_SANITIZE_STRING);
                if(strlen($return) > 0)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (string; $type)!";
                    return false;
                }
                break; //useless; I still keep it as it looks better
                
            case "cr_note_title_":
            case "cr_mat_note_":
                $sanitized = filter_var($text, FILTER_SANITIZE_STRING);
                return true;
                
                
            /*integers (possibly with whitespaces)*/
            case "cr_mat_count_":
            case "cr_phone":
                $return = filter_var($text, FILTER_SANITIZE_NUMBER_INT);
                if(strlen($return) > 0)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (int;$type)!";
                    return false;
                }
                break;
            case "cr_mobile":
                $sanitized = filter_var($text, FILTER_SANITIZE_NUMBER_INT);
                return true;
            
            /*float*/
            case "cr_mat_price_":
                $return = filter_var($text, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if(strlen($return) > 0)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (float; $type)!";
                    return false;
                }
                break;
            
            /*tinymce html*/
            case "cr_desc":
            case "cr_note_":
                /*<from tinymce.com>*/
                $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
                $allowedTags.='<li><ol><ul><span><div><br><ins><del>';
                $sanitized = strip_tags(stripslashes($text),$allowedTags);
                /*</from tinymce.com>*/
                return true;
                break;
                
            /*gender enum*/
            case "cr_gender":
                if($text == "Frau")
                {
                    $sanitized = "f";
                    return true;
                }
                if($text == "Herr")
                {
                    $sanitized = "m";
                    return true;
                }
                if($text == "Firma")
                {
                    $sanitized = "b";
                    return true;
                }
                
                $error = "Ungültiges Geschlecht ausgewählt.";
                return false;
                break;
                
            /*material state enum*/
            case "cr_mat_state_":
                if($text == "Bestellt")
                {
                    $sanitized = "order";
                    return true;
                }
                if($text == "Geliefert")
                {
                    $sanitized = "arrived";
                    return true;
                }
                if($text == "Benutzt")
                {
                    $sanitized = "used";
                    return true;
                }
                
                $error = "Ungültiger Materialstatus.";
                return false;
                break;
                
            /*check user enum*/
            case "cr_resp":
                $db = new Database();
                $escaped = $db->escape($text);
                $ret = $db->run("select uId from users where uName='$escaped'");
                if($ret == false)
                {
                    $error = "Benutzer konnte nicht gefunden werden";
                    return false;
                }
                $sanitized = $ret->fetch_assoc()["uId"];
                return true;
                
                //TODO implement; check if user exists; return user ID;
                return true;
                break;
            
            case "cr_mat_delivery_":
                $time = strtotime($text);
                if($time == false)
                {
                    $error = "Unbekanntes Zeitformat für die Materiallieferung angegeben.";
                    return false;
                }
                $sanitized = date("Y-m-d  H:i:s",$time);
                return true;
                
                break;
            
            case "cr_date_":
                $time = strtotime($text);
                if($time == false)
                {
                    $error = "Unbekanntes Zeitformat im Kalander.";
                    return false;
                }
                $sanitized = date("Y-m-d",$time);
                return true;
                break;
            case "cr_date_statime_":
            case "cr_date_stotime_":
                $time = strtotime($text);
                if($time == false)
                {
                    $error = "Unbekanntes Zeitformat im Kalender ($type)";
                    return false;
                }
                $sanitized = date("H:i:s",$time);
                return true;
                break;
            default:
                $error = "unbekanntes feld: $type_";
                return false;
                break;
        }
        
    }
    
    /* $type is the type (the name in the post basically)
     * $text is it's contents
     * 
     * $sanitized is the sanitized string (not escaped!)
     * $error is the error that was encountered (if there was one)
     * returns a true if valid
     */
    public function checkEdit($type, $text, &$sanitized, &$error)
    {
        /*removing all numbers in $type*/
        
        $type_ = preg_replace('/[0-9]*/', '', $type);
        
        switch ($type_) {
            /*alphanumeric*/
            case "cr_name":
            case "cr_city":
            case "cr_address":
            case "cr_mat_title_":
            case "cr_date_desc_":
            case "cr_file_":
                $return = filter_var($text, FILTER_SANITIZE_STRING);
                if(strlen($return) > 0)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (string; $type)!";
                    return false;
                }
                break; //useless; I still keep it as it looks better
                
            case "cr_note_title_":
            case "cr_mat_note_":
            case "cr_title":
                $sanitized = filter_var($text, FILTER_SANITIZE_STRING);
                return true;
                
                
            /*integers (possibly with whitespaces)*/
            case "cr_mat_count_":
            case "cr_phone":
                $return = filter_var($text, FILTER_SANITIZE_NUMBER_INT);

                if(strlen($return) > 0)
               {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (int;$type)!";
                    return false;
                }
                break;
            case "cr_mobile":
                $sanitized = filter_var($text, FILTER_SANITIZE_NUMBER_INT);
                return true;
            
            /*float*/
            case "cr_mat_price_":
                $return = filter_var($text, FILTER_SANITIZE_NUMBER_FLOAT);
                if(strlen($return) > 0)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (float; $type)!";
                    return false;
                }
                break;
            
            /*tinymce html*/
            case "cr_desc":
            case "cr_note_":
                /*<from tinymce.com>*/
                $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
                $allowedTags.='<li><ol><ul><span><div><br><ins><del>';
                $sanitized = strip_tags(stripslashes($text),$allowedTags);
                /*</from tinymce.com>*/
                return true;
                break;
                
            /*gender enum*/
            case "cr_gender":
                if($text == "Frau")
                {
                    $sanitized = "f";
                    return true;
                }
                if($text == "Herr")
                {
                    $sanitized = "m";
                    return true;
                }
                if($text == "Firma")
                {
                    $sanitized = "b";
                    return true;
                }
                
                $error = "Ungültiges Geschlecht ausgewählt.";
                return false;
                break;
                
            /*material state enum*/
            case "cr_mat_state_":
                if($text == "Bestellt")
                {
                    $sanitized = "order";
                    return true;
                }
                if($text == "Geliefert")
                {
                    $sanitized = "arrived";
                    return true;
                }
                if($text == "Benutzt")
                {
                    $sanitized = "used";
                    return true;
                }
                
                $error = "Ungültiger Materialstatus.";
                return false;
                break;
                
            /*check user enum*/
            case "cr_resp":
                $db = new Database();
                $escaped = $db->escape($text);
                $ret = $db->run("select uId from users where uName='$escaped'");
                if($ret == false)
                {
                    $error = "Benutzer konnte nicht gefunden werden";
                    return false;
                }
                $sanitized = $ret->fetch_assoc()["uId"];
                return true;
                
                //TODO implement; check if user exists; return user ID;
                return true;
                break;
            
            case "cr_mat_delivery_":
                $time = strtotime($text);
                if($time == false)
                {
                    $error = "Unbekanntes Zeitformat für die Materiallieferung angegeben.";
                    return false;
                }
                $sanitized = date("Y-m-d  H:i:s",$time);
                return true;
                
                break;
            
            case "cr_date_":
                $time = strtotime($text);
                if($time == false)
                {
                    $error = "Unbekanntes Zeitformat im Kalander.";
                    return false;
                }
                $sanitized = date("Y-m-d",$time);
                return true;
                break;
            case "cr_date_statime_":
            case "cr_date_stotime_":
                $time = strtotime($text);
                if($time == false)
                {
                    $error = "Unbekanntes Zeitformat im Kalender ($type)";
                    return false;
                }
                $sanitized = date("H:i:s",$time);
                return true;
                break;
                
            case "cr_date_id_":
            case "cr_note_id_":
            case "cr_file_id_":
            case "cr_mat_id_":
            case "cr_id":
                //TODO implement
                return true;
                break;

            default:
                $error = "unbekanntes feld: $type_";
                return false;
                break;
        }
        
    }
}


class newOrder
{
    private $address;
    private $city;
    private $gender;
    private $name;
    private $mobile;
    private $phone;
    private $title;
    private $resp;
    private $desc;
    
    
    /*arrays*/
    private $date;
    private $date_statime;
    private $date_stotime;
    private $date_desc;
    
    private $file;
    private $file_Ids;
    private $fileTmp;
    
    private $note_title;
    private $note;
    
    private $mat_count;
    private $mat_title;
    private $mat_note;
    private $mat_state;
    private $mat_delivery;
    private $mat_price;
    
    private $success = true;
    private $errmsg;
    
    private $db;
    
    
    function __construct() {
        $this->db = new Database();
    }








    /*processes the post parameter and extracts the data*/
    public function processAll(&$success, &$error)
    {
        /*address related*/
        $this->address = $this->handle("cr_address");
        $this->city = $this->handle("cr_city");
        $this->gender = $this->handle("cr_gender");
        $this->name = $this->handle("cr_name");
        $this->mobile = $this->handle("cr_mobile");
        $this->phone = $this->handle("cr_phone");
        
        /*Order & order description*/
        $this->title = $this->handle("cr_title");
        $this->resp = $this->handle("cr_resp");
        $this->desc = $this->handle("cr_desc");
        
        /*materials*/
        $i = 1;

        while(isset($_REQUEST["cr_mat_count_$i"]) && $this->success)
        {
            $this->mat_count[$i] = $this->handle("cr_mat_count_$i");
            $this->mat_title[$i] = $this->handle("cr_mat_title_$i");
            $this->mat_note[$i] = $this->handle("cr_mat_note_$i");
            $this->mat_state[$i] = $this->handle("cr_mat_state_$i");
            $this->mat_delivery[$i] = $this->handle("cr_mat_delivery_$i");
            $this->mat_price[$i] = $this->handle("cr_mat_price_$i");
            $i++;
        }
        
        /*notes*/
        $i = 1;
        while(isset($_REQUEST["cr_note_title_$i"]) && $this->success)
        {
            $this->note_title[$i] = $this->handle("cr_note_title_$i");
            $this->note[$i] = $this->handle("cr_note_$i");
            $i++;
        }
        
        /*dates*/
        $i = 1;
        while(isset($_REQUEST["cr_date_$i"]) && $this->success)
        {
            $this->date[$i] = $this->handle("cr_date_$i");
            $this->date_statime[$i] = $this->handle("cr_date_statime_$i");
            $this->date_stotime[$i] = $this->handle("cr_date_stotime_$i");
            $this->date_desc[$i] = $this->handle("cr_date_desc_$i");
            $i++;
        }
        
         /*files*/
        $i = 1;
        
        //TODO remove this debug
        //TODO check why the files are not posted
        //file_put_contents("../uploads/info.txt", print_r($_FILES, true));
        //file_put_contents("../uploads/info.txt", print_r($_REQUEST, true), FILE_APPEND);
    
        while(isset($_FILES["cr_file_$i"]))
        {
            $file = $_FILES["cr_file_$i"];
            if($file['error'] == UPLOAD_ERR_OK)
            {
                $_SESSION["cr_file_$i"] = $file['name'];
                $this->file[$i] = $this->handle("cr_file_$i");
                $this->fileTmp[$i] = $file['tmp_name'];
                $i++;
            }
        }
        $this->writeDB();
        $this->saveFiles();
        
        
        $success = $this->success;
        $error = $this->errmsg;
    }
    
    /*saves all the attached files*/
    private function saveFiles()
    {
        if($this->success)
        {
            $i = 1;
            while(isset($this->file[$i]))
            {
                move_uploaded_file($this->fileTmp[$i], "../uploads/$this->file_Ids[$i]");
            }
        }
        //TODO implement
    }
    
    
    
    /*writes the contents into the database*/
    private function writeDB()
    {
        $db = $this->db;
        $user = new user();
        $datetime = date("Y-m-d  H:i:s",time());
        
        if(isset($_SESSION['user']) && isset($_SESSION['auth']))
        {
            //user needs to be authenticate
            if($user->authenticate($_SESSION['user'], $_SESSION['auth']) && $this->success)
            {
        
                /*inserting client information into the database*/
                $type = "retail";
                if($this->gender == 'b')
                    $type = "business";
                
                $db->run("insert into clients (cName, cType, cGender, cPhone, cMobile, cStreet, cCity)
                          values ('$this->name','$type','$this->gender','$this->phone','$this->mobile','$this->address','$this->city')");

                
                $ret = $db->run("select max(cId) from clients");
                
                /*insert into jobs table*/
                $userid = $ret->fetch_assoc()["max(cId)"];
                $creatorId = $user->getId();
                $assigneeId = $this->resp;
                $db->run("insert into jobs (jName, jDesc, jStage, jResp, Creator_uId, jCreationDate, clients_cId)
                          values ('$this->title','$this->desc','evaluation','$assigneeId','$creatorId','$datetime','$userid')");

                $ret = $db->run("select max(jId) from jobs");
                $jobId = $ret->fetch_assoc()["max(jId)"];
                
                $materialId;
                
                $i=1;
                while(isset($this->mat_title[$i]))
                {
                    $title = $this->mat_title[$i];
                    $note = $this->mat_note[$i];
                    $state = $this->mat_state[$i];
                    $delivery = $this->mat_delivery[$i];
                    $price = $this->mat_price[$i];
                    $count = $this->mat_count[$i];
                    
                    $db->run("insert into materials (mName, mDesc, mState, mDelDate, mPrice, mQuantity, jobs_jId)
                          values ('$title','$note','$state','$delivery','$price','$count','$jobId')");
                    $i++;
                }
                
                $i=1;
                while(isset($this->file[$i]))
                {
                    $file = $this->file[$i];
                    $db->run("insert into comAttach (coResource, coDate, users_uId, jobs_jId, jobs_clients_cId)
                          values ('$file', '$datetime', '$creatorId', '$jobId', '$userid')");

                    $ret = $db->run("select max(coAtId) from comAttach");
                    $this->file_Ids[$i] = $ret->fetch_assoc()["max(coAtId)"];
                    $i++;
                }
                
                
                $i=1;
                while(isset($this->note_title[$i]))
                {
                    $title = $this->note_title[$i];
                    $note = $this->note[$i];
                    $db->run("insert into comText (coTitle, coText, coDate, jobs_jId, users_uId)
                          values ('$title', '$note', '$datetime', '$jobId', '$creatorId')");
                    $i++;
                }
                
                $i = 1;
                while (isset($this->date[$i]))
                {
                    $date = $this->date[$i];
                    $start = $this->date_statime[$i];
                    $stop = $this->date_stotime[$i];
                    $desc = $this->date_desc[$i];
                    $db->run("insert into shedule (sStart, sStop, sComment, jobs_jId, users_uId)
                          values ('$date $start', '$date $stop', '$desc', '$jobId', '$creatorId')");
                    $i++;
                }
                
                //TODO: history reenable; usw
            //    $uname = $user->getUsername();
            //    $db->run("insert into history (hTime, hType, hText, jobs_jId)
            //              values ('$datetime', 'Neuer Auftrag', '$uname hat einen neuen Auftrag erstellt.', '$jobId')");
                

            }
            else
            {
                if($this->success)
                {
                    $this->errmsg = "Autentifizierung fehlgeschlagen";
                    $this->success = false;
                }
            }
        }
        else
        {
            $this->errmsg = "Autentifizierung benötigt";
            $this->success = false;
        }
    }


    /*reads a certain value out of $_REQUEST[] and validates it's contents*/
    public function handle($name)
    {
        $val = $this->retrieve($name);
        if($this->success == true)
        {
            if(Validate::check($name, $val, $sanitized, $this->errmsg) == true)
            {
                $sanitized = $this->db->escape($sanitized);
                return $sanitized;
            }
            else
            {
                $this->success = false;
                return $val;
            }
        }
        
    }

    /*checks the $_REQUEST for it and returns the value*/
    private function retrieve($name)
    {
        if($this->success == true)
        {
            if(isset($_REQUEST[$name]))
            {
                return $_REQUEST[$name];
            }
             else 
            {
                $this->success = false;
                $this->errmsg = "Field not set: $name";
                return NULL;
            }
        }
    }
}

class newEdit
{
    private $address;
    private $city;
    private $gender;
    private $name;
    private $mobile;
    private $phone;
    private $resp;
    private $desc;
    
    private $jId;
    
   

    
    /*arrays*/
    private $date_statime;
    private $date_stotime;
    private $date_desc;
    private $date_id;
    
    private $file;
    private $file_Ids;
    private $file_oldIds;
    
    private $note_title;
    private $note;
    private $note_id;
    
    private $mat_count;
    private $mat_title;
    private $mat_note;
    private $mat_state;
    private $mat_delivery;
    private $mat_price;
    private $mat_id;
    
    private $success = true;
    private $errmsg;
    
    private $db;
    
    
    function __construct() {
        $this->db = new Database();
    }








    /*processes the post parameter and extracts the data*/
    public function processAll(&$success, &$error)
    {
        
        
        if(!isset($_REQUEST["action"]))
        {
            $success = false;
            $error = "incomplete post";
        }
        
        
        
        /*address related*/
        $this->address = $this->handle("cr_address");
        $this->city = $this->handle("cr_city");
        $this->gender = $this->handle("cr_gender");
        $this->name = $this->handle("cr_name");
        $this->mobile = $this->handle("cr_mobile");
        $this->phone = $this->handle("cr_phone");
        
        //TODO check why this doesn't work
        $this->jId = $this->handle("cr_id");
        
        $this->jId = $_REQUEST["cr_id"];
        
        /*Order & order description*/
        $this->resp = $this->handle("cr_resp");
        $this->desc = $this->handle("cr_desc");
        
        switch($_REQUEST["action"])
        {
            case "delete":
                $this->db->run("DELETE FROM jobs WHERE jId = '$this->jId'");
                $success = true;
                return;
            //TODO complete switch statement;
        }
        
        /*materials*/
        $i = 1;

        while(isset($_REQUEST["cr_mat_count_$i"]) && $this->success)
        {
            $this->mat_count[$i] = $this->handle("cr_mat_count_$i");
            $this->mat_title[$i] = $this->handle("cr_mat_title_$i");
            $this->mat_note[$i] = $this->handle("cr_mat_note_$i");
            $this->mat_state[$i] = $this->handle("cr_mat_state_$i");
            $this->mat_delivery[$i] = $this->handle("cr_mat_delivery_$i");
            $this->mat_price[$i] = $this->handle("cr_mat_price_$i");
            $this->mat_id[$i] = $this->handle("cr_mat_id_$i");
            $i++;
        }
        
        /*notes*/
        $i = 1;
        while(isset($_REQUEST["cr_note_title_$i"]) && $this->success)
        {
            $this->note_title[$i] = $this->handle("cr_note_title_$i");
            $this->note[$i] = $this->handle("cr_note_$i");
            $this->note_id[$i] = $this->handle("cr_note_id_$1");
            $i++;
        }
        
        /*dates*/
        $i = 1;
        while(isset($_REQUEST["cr_date_$i"]) && $this->success)
        {
            $this->date[$i] = $this->handle("cr_date_$i");
            $this->date_statime[$i] = $this->handle("cr_date_statime_$i");
            $this->date_stotime[$i] = $this->handle("cr_date_stotime_$i");
            $this->date_desc[$i] = $this->handle("cr_date_desc_$i");
            $this->date_id[$i] = $this->handle("cr_date_id_$i");
            $i++;
        }
        
         /*files*/
        $i = 1;
        while(isset($_REQUEST["cr_file_$i"]) && $this->success)
        {
            $this->file[$i] = $this->handle("cr_file_$i");
            $this->file_oldIds[$i] = $this->handle("cr_file_id_$i");
            $i++;
        }
        
        $this->writeDB();
        $this->saveFiles();
        
        
        $success = $this->success;
        $error = $this->errmsg;
    }
    
    /*saves all the attached files*/
    private function saveFiles()
    {
        //TODO implement
    }
    
    
    
    /*writes the contents into the database*/
    private function writeDB()
    {
        //TODO implement
        $db = $this->db;
        $user = new user();
        $datetime = date("Y-m-d  H:i:s",time());
        
        if(isset($_SESSION['user']) && isset($_SESSION['auth']))
        {
            //user needs to be authenticate
            if($user->authenticate($_SESSION['user'], $_SESSION['auth']))
            {
        
                /*inserting client information into the database*/
                $type = "retail";
                if($this->gender == 'b')
                    $type = "business";
                $ret = $db->run("select clients_cId from jobs where jId = '$this->jId'");
                $cid = $ret->fetch_assoc()["clients_cId"];
                
                $db->run("update clients 
                          set cName='$this->name', cType='$type', cGender='$this->gender', cPhone= '$this->phone', cMobile='$this->mobile', cStreet='$this->address', cCity='$this->city'
                          where cId = '$cid'");

                
                
                
                /*insert into jobs table*/
//                $ret = $db->run("select max(cId) from clients");
//                $userid = $ret->fetch_assoc()["max(cId)"];
                $userid = $cid;
                $creatorId = $user->getId();
                $assigneeId = $this->resp;
                $db->run("update jobs
                          set jDesc='$this->desc', jResp='$assigneeId', Creator_uId='$creatorId', jCreationDate='$datetime', clients_cId='$userid'
                          where jId = '$this->jId'");

                $jobId = $this->jId;
                
                $materialId;
                
                $i=1;
                while(isset($this->mat_title[$i]))
                {
                    $title = $this->mat_title[$i];
                    $note = $this->mat_note[$i];
                    $state = $this->mat_state[$i];
                    $delivery = $this->mat_delivery[$i];
                    $price = $this->mat_price[$i];
                    $count = $this->mat_count[$i];
                    $id = $this->mat_id[$i];
                    
                    if($id == '')
                    {
                        $db->run("insert into materials (mName, mDesc, mState, mDelDate, mPrice, mQuantity, jobs_jId)
                              values ('$title','$note','$state','$delivery','$price','$count','$jobId')");
                    }
                    else
                    {
                        $db->run("  update materials 
                                    set mDesc='$note', mState='$state', mDelDate='$delivery', mPrice='$price', mQuantity='$count', jobs_jId='$jobId'
                                    where mId = '$id'");
                    }
                    
                    $i++;
                }
                
                $i=1;
                while(isset($this->file[$i]))
                {
                    //TODO update files n stuff
                    $file = $this->file[$i];
                    $db->run("insert into comAttach (coResource, coDate, users_uId, jobs_jId, jobs_clients_cId)
                          values ('$file', '$datetime', '$creatorId', '$jobId', '$userid')");

                    $ret = $db->run("select max(coAtId) from comAttach");
                    $this->file_Ids[$i] = $ret->fetch_assoc()["max(coAtId)"];
                    $i++;
                }
                
                
                $i=1;
                while(isset($this->note_title[$i]))
                {
                    $id = $this->note_id[$i];
                    $title = $this->note_title[$i];
                    $note = $this->note[$i];
                    if($id == '')
                    {
                        $db->run("insert into comText (coTitle, coText, coDate, jobs_jId, users_uId)
                          values ('$title', '$note', '$datetime', '$jobId', '$creatorId')");
                    }
                    else
                    {
                        $db->run("  update comText (coText, coDate, jobs_jId, users_uId)
                                    values ('$note', '$datetime', '$jobId', '$creatorId')
                                    where coTextId = '$id'");
                    }
                    $i++;
                }
                
                $i = 1;
                while (isset($this->date[$i]))
                {
                    $date = $this->date[$i];
                    $start = $this->date_statime[$i];
                    $stop = $this->date_stotime[$i];
                    $desc = $this->date_desc[$i];
                    $id = $this->date_id[$i];
                    
                    if($id == '')
                    {
                        $db->run("  insert into shedule (sStart, sStop, sComment, jobs_jId, users_uId)
                                    values ('$date $start', '$date $stop', '$desc', '$jobId', '$creatorId')");
                    }
                    else
                    {
                        $db->run("  update shedule (sStart, sStop, sComment, jobs_jId, users_uId)
                                    values ('$start', '$stop', '$desc', '$jobId', '$creatorId')
                                    where sId = '$id'");
                    }
                    $i++;
                }
                
                //TODO: history reenable; usw
            //    $uname = $user->getUsername();
            //    $db->run("insert into history (hTime, hType, hText, jobs_jId)
            //              values ('$datetime', 'Neuer Auftrag', '$uname hat einen neuen Auftrag erstellt.', '$jobId')");
                

            }
        }
        else
        {
            $this->errmsg = "Autentifizierung benötigt";
            $this->success = false;
        }
    }


    /*reads a certain value out of $_REQUEST[] and validates it's contents*/
    public function handle($name)
    {
        $val = $this->retrieve($name);
        if($this->success == true)
        {
            if(Validate::checkEdit($name, $val, $sanitized, $this->errmsg) == true)
            {
                $sanitized = $this->db->escape($sanitized);
                return $sanitized;
            }
            else
            {
                $this->success = false;
                return $val;
            }
        }
        
    }

    /*checks the $_REQUEST for it and returns the value*/
    private function retrieve($name)
    {
        if($this->success == true)
        {
            if(isset($_REQUEST[$name]))
            {
                return $_REQUEST[$name];
            }
            
            //TODO
          /*   else 
            {
                $this->success = false;
                $this->errmsg = "Field not set: $name";
                return NULL;
            }*/
        }
    }
}