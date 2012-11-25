<?php

require_once 'config.php';

/* Database class
 *
 * Handles all the Data transfers between the page/php and the database
 */

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
        return $this->db->query($statement);  
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
            case "cr_note_title_":
            case "cr_date_desc_":
            case "cr_date_":
            case "cr_mat_note_":
            case "cr_file_":
                $return = filter_var($text, FILTER_SANITIZE_STRING);
                if(strlen($return) > 1)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (string)!";
                    return false;
                }
                break; //useless; I still keep it as it looks better
            
            /*integers (possibly with whitespaces)*/
            case "cr_mat_count_":
            case "cr_mobile":
            case "cr_phone":
                $return = filter_var($text, FILTER_SANITIZE_NUMBER_INT);
                if(strlen($return) > 1)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (int)!";
                    return false;
                }
                break;
            
            /*float*/
            case "cr_mat_price_":
                $return = filter_var($text, FILTER_SANITIZE_NUMBER_FLOAT);
                if(strlen($return) > 1)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (float)!";
                    return false;
                }
                break;
            
            /*tinymce html*/
            case "cr_desc":
            case "cr_note_":
                /*<from tinymce.com>*/
                $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
                $allowedTags.='<li><ol><ul><span><div><br><ins><del>';
                $return = strip_tags(stripslashes($text),$allowedTags);
                /*</from tinymce.com>*/
                
                if(strlen($return) > 1)
                {
                    $sanitized = $return;
                    return true;
                }
                else
                {
                    $error = "Nicht genügend gültige Zeichen (html)!";
                    return false;
                }
                
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
                //TODO implement; check if user exists; return user ID;
                return true;
                break;
            
            case "cr_mat_delivery_":
            case "cr_date_statime_":
            case "cr_date_stotime_":
                //TODO check how the date is entered; verify; convert to mysql date
                
                return true;
                break;
                

            default:
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
    public function processAll()
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
        while(isset($_POST["cr_mat_count_$i"]) && $this->success)
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
        while(isset($_POST["cr_note_title_$i"]) && $this->success)
        {
            $this->note_title[$i] = $this->handle("cr_note_title_$i");
            $this->note[$i] = $this->handle("cr_note_$i");
            $i++;
        }
        
        /*dates*/
        $i = 1;
        while(isset($_POST["cr_date_$i"]) && $this->success)
        {
            $this->date[$i] = $this->handle("cr_date_$i"+$i);
            $this->date_statime[$i] = $this->handle("cr_date_statime_$i");
            $this->date_stotime[$i] = $this->handle("cr_date_stotime_$i");
            $this->date_desc[$i] = $this->handle("cr_date_desc_$i");
            $i++;
        }
        
         /*files*/
        $i = 1;
        while(isset($_POST["cr_file_$i"]) && $this->success)
        {
            $this->file[$i] = $this->handle("cr_file_$i");
            $i++;
        }
        
        $this->writeDB();
        $this->saveFiles();
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
            if( !$user->authenticate($_SESSION['user'], $_SESSION['auth']))
            {
        
                /*inserting client information into the database*/
                $type = "retail";
                if($this->gender == 'b')
                    $type = "business";
                $db->run("insert into clients (cName, cType, cGender, cPhone, cMobile, cStreet, cCity)
                          values ('$this->name','$type','$this->gender','$this->phone','$this->mobile','$this->address','$this->city')");

                $ret = $db->run("select max(cId) from clients");
                
                /*insert into jobs table*/
                $userid = $ret->fetch_assoc()["cId"];
                $creatorId = $user->getId();
                $assigneeId = $this->resp;
                $db->run("insert into jobs (jName, jDesc, jStage, jResp, Creator_uId, jCreationDate, clients_cId)
                          values ('$this->title','$this->desc','evaluation','$assigneeId','$creatorId','$datetime','$userid')");

                $ret = $db->run("select max(jId) from jobs");
                $jobId = $ret->fetch_assoc()["jId"];
                
                $materialId;
                
                $i=1;
                while(isset($this->mat_title[$i]))
                {
                    $db->run("insert into materials (mName, mDesc, mState, mDelDate, mPrice, mQuantity, jobs_jId)
                          values ('$this->mat_title[$i]','$this->mat_note[$i]','$this->mat_state[$i]','$this->mat_delivery[$i]','$this->mat_price[$i]','$this->mat_count[$i]','$jobId')");
                }
                
                $i=1;
                while(isset($this->file[$i]))
                {
                    $db->run("insert into comAttach (coResource, coDate, users_uId, jobs_jId, jobs_clients_cId)
                          values ('$this->file[$i]', '$datetime', '$creatorId', '$jobId', '$userid')");

                    $ret = $db->run("select max(coAtId) from comAttach");
                    $this->file_Ids[$i] = $ret->fetch_assoc()["coAtId"];
                }
                
                
                $i=1;
                while(isset($this->note_title[$i]))
                {
                    $db->run("insert into comText (coTitle, coText, coDate, jobs_jId, users_uId)
                          values ('$this->note_title[$i]', '$this->note[$i]', '$datetime', '$jobId', '$creatorId')");
                }

                $db->run("insert into comText (hTime, hType, hText, jobs_jId)
                          values ('$datetime', 'Neuer Auftrag', '$user->getUsername() hat einen neuen Auftrag erstellt.', '$jobId')");
                

            }
        }
        
        
        
        
        
    }


    /*reads a certain value out of $_POST[] and validates it's contents*/
    public function handle($name)
    {
        $val = $this->retrieve($name);
        if($this->success == true)
        {
            if(Validate::check($name, $val, $sanitized, $error) == true)
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

    /*checks the $_POST for it and returns the value*/
    private function retrieve($name)
    {
        if($this->success == true)
        {
            if(isset($_POST[$name]))
            {
                return $_POST[$name];
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