<?php


class Auth
{

    private static $_instance;
    private $_currentUser;  // current signed in user object

    private function __construct(){}
    private function __clone(){}


    /**
     * initialisation
     */
    public static function init()
    {
        session_start();
    }


    /**
     * Return singleton instance
     * @return mixed
     */
    public static function getInstance()
    {
        if(static::$_instance === null)
        {
            static::$_instance = new Auth();
        }
        return static::$_instance;
    }



    public function login($email, $password)
    {
        $user = User::authenticate($email, $password);

        if($user !== null)
        {
            $this->_currentUser = $user;
            $_SESSION['user_id'] = $user->id;

            session_regenerate_id();    // prevent session hijacking
            return true;
        }
        return false;
    }



    /**
     * Get the current logged in user
     *
     * @return mixed  User object if logged in, null otherwise
     */
    public function getCurrentUser()
    {
        if($this->_currentUser === null)
        {
            if(isset($_SESSION['user_id'])){
                // Cache the object so that in a single request the data is loaded from the database only once.
                $this->_currentUser = User::findByID($_SESSION['user_id']);
            }
        }
        return $this->_currentUser;
    }



    /**
     * Boolean indicator of whether the user is logged in or not
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->getCurrentUser() !== null;
    }





}
