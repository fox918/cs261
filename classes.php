<?php
/*
 * User class
 * handles all the things concerning an user:
 * access rights, name, department, etc
 * login and logout
 */
class user
{
    //variables
    private $name = 'Gast';
    private $loggedIn = true; //TODO DEBUG ONLY
    private $role = 'guest';  //current role the user has
    private $roles = array('admin','worker','store','guest');

    //language things
    private $lang_roles = array(
        'admin'=>'Verwaltung',
        'worker'=>'Arbeiter',
        'store'=>'Lagerist',
        'guest'=>'nicht angemeldet'
    );
    
    
    //log the user in
    public function login($username,$password)
    {
        //TODO real auth
    }
    
    // wether a user is validated or not, false if not logged in, true otherwise
    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    // returns true if the user has the authority to do $input
    public function hasAuth($input)
    {
        //TODO implement
        return true;
    }

    //printing
    
    //print username
    public function printUsername()
    {
        echo $this->name;
    }

    //print department
    public function printDepartment()
    {
        echo $this->lang_roles[$this->role];
    }


}

/*
 * page class
 * handles all the html
 */
class page
{   
    //variables
    private $page = 'login'; //current page, default is login
    private $pages = Array('login','create','list','archive','admin','edit');

    private $user;

    
    
    
    
    
    //language things
    private $lang_pages = Array(
        'login'=>'Anmelden',
        'create'=>'Erfassen',
        'list'=>'Aufträge',
        'archive'=>'Archiv',
        'admin'=>'Verwaltung',
        'edit'=>'Auftragsansicht'
    );
    //setters
    
    //set the page to display
    public function setPage($input)
    {
        $input = strtolower($input);
        if(isset($input) && in_array($input,$this->pages,TRUE))
        {
            if($this->user->isLoggedIn() && $this->user->hasAuth($input))
            {
                $this->page = $input;
            }
        }
    }

    //set subpage if necessary
    public function setSubPage()
    {

    }

    //set the order (necessary for edit)
    public function setOrder()
    {

    }

    //set the user 
    public function setUser($input)
    {
        if(isset($input))
        {
            $this->user = $input;
            if($this->user->isLoggedIn())
            {
                $this->page = 'list';
            }
        }
    }

    //printing functions
    
    //print the page title
    public function printTitle()
    {
        echo $this->lang_pages[$this->page]." | Auftragsverwaltung";
    }

    //print the article of the page
    public function printArticle()
    {

    } 
}

