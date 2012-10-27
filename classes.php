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
    private $id = 0; //user id
    private $name = 'Gast'; //username
    private $loggedIn = false; //if logged in 
    private $authToken = null; //auth token of the current session
    private $role = 'guest';  //current role the user has
    private $roles = array('admin','worker','store','guest');

    //language things
    private $lang_roles = array(
        'admin'=>'Verwaltung',
        'worker'=>'Arbeiter',
        'store'=>'Lagerist',
        'guest'=>'nicht angemeldet'
    );

    /*
     *  Security functions:
     */

    //log the user in
    public function login($username,$password)
    {
        //TODO real auth
        $this->loggedIn = true;
        $this->authToken = 'asdf';
        $this->name = $username;
    }

    //check if user is valid (when he already is logged in)
    public function authenticate($username,$authtoken)
    {
        //TODO real auth
        //TODO evtl. implement second authtoken with COOKIE
        $this->loggedIn = true;
        $this->name = $username;
        return true;
    }

    // wether a user is validated or not, false if not logged in, true otherwise
    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    // returns true if the user has the authority to do $input
    public function hasAuth($page, $action)
    {
        if($this->role == 'admin'){
            return true;
        }
    /*
     * Actions:
     * view, change
     */




        //TODO implement interaction

        //TODO delete after DB implementation
        return true;
    } 

        /*
         * getter functions
         */

        //get username
        public function getUsername()
        {
        return $this->name;
    }

    //get department
    public function getDepartment()
    {
        return $this->lang_roles[$this->role];
    }

    //returns the authToken of the current session
    public function getAuthToken(){
        return $this->authToken;
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
    private $pages = Array('login','create','list','archive','admin','edit','error');
    private $subpage; //current subpage
    private $subpages;

    private $user; 



    //language things
    //titles of the pages
    private $lang_pages = Array(
        'login'=>'Anmelden',
        'create'=>'Erfassen',
        'list'=>'Aufträge',
        'archive'=>'Archiv',
        'admin'=>'Verwaltung',
        'edit'=>'Auftragsansicht',
        'error'=>'Fehler'
    );
    //setters

    //set the page to display
    public function setPage($input)
    {
        $result = 'list';

        if( isset($input)){
            $result = $input;
        }

        //not valid input
        $result = strtolower($result);
        if( !in_array($result,$this->pages,TRUE))
        {
            $result = 'error';
        }
        //no auth
        if( !$this->user->hasAuth($result,'view'))
        {
            $result = 'error';        
        }

        //not logged in
        if( !$this->user->isLoggedIn()){
            $result = 'login';
        }

        $this->page = $result;
    }

    //set subpage if necessary
    public function setSubPage($input)
    {

    }

    //set the order (necessary for edit)
    public function setOrder($input)
    {

    }

    //set the user 
    public function setUser($input)
    {
        if(isset($input))
        {
            $this->user = $input;
        }
    }

    //printing functions

    //print the page title
    public function printTitle()
    {
        echo $this->lang_pages[$this->page]." | Auftragsverwaltung";
    }

    //print the menu
    public function printMenu()
    {
        $menuentries = Array(
            'create'=>'Erfassen',
            'list'=>'Aufträge',
            'archive'=>'Archiv',
            'admin'=>'Verwaltung',
        );

        echo '<ul>';
        foreach($menuentries as $key => $title)
        {
            $class='';
            if($key == $this->page){$class='class="active"';}
            if($this->user->hasAuth($key,'view'))
            {
                echo '<li>'.
                    '<a '.$class.' href="index.php?page='.$key.'"> '.
                    $title.
                    '</a>'.
                    '</li>';
            }    
        }
        echo '</ul>';
    }

    //print the article of the page
    public function printArticle()
    {    
        require './templates/'.$this->page.'.php';
    } 
    public function includeJS()
    {
        echo '<script type="text/javascript" src="./js/'.$this->page.'.js"></script>';
    }
}

