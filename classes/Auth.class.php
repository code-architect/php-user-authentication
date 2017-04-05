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



    public function login($email, $password, $remember_me)
    {
        $user = User::authenticate($email, $password);

        if($user !== null)
        {
            $this->_currentUser = $user;
            $this->_loginUser($user);

            // remember me login
            if($remember_me)
            {
                $expiry = time() + 60 * 60 * 24 * 90;
                $token = $user->rememberLogin($expiry);

                // set the "remember me" cookie with the token value and expiry date
                if($token !== false)
                {
                    setcookie('remember_token',  $token, $expiry);
                }
            }

            return true;
        }
        return false;
    }



    /**
     * Login the user to the session
     *
     * @param User $user  User object
     * @return void
     */
    private function _loginUser($user) {

        // Store the user ID in the session
        $_SESSION['user_id'] = $user->id;

        // Regenerate the session ID to prevent session hijacking
        session_regenerate_id();
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
            } else{
                //log in from the cookie if set
                $this->_currentUser = $this->_loginFromCookie();
            }
        }
        return $this->_currentUser;
    }


    /**
     * Log the user in from the remember me cookie
     */
    private function _loginFromCookie()
    {
        if(isset($_COOKIE['remember_token']))
        {
            // find the user that has the token in the database
            $user = User::findByRememberToken(sha1($_COOKIE['remember_token']));
            if($user !== null)
            {
                $this->_loginUser($user);
                return $user;
            }
        }
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


    /**
     * Logging out the user
     */
    public function logout()
    {
        //forget the remembered login if set
        if(isset($_COOKIE['remember_me']))
        {
            //delete the record from the database
            $this->getCurrentUser()->forgetLogin(sha1($_COOKIE['remember_me']));

            //delete the cookie in the user system
            setcookie('remember_token', '', time() - 3600);
        }

        // remove all variables and destroy the session
        $_SESSION = [];
        session_destroy();
    }


    /**
     * Without login users are not welcome on this certain page
     */
    public function requireLogin()
    {
        if(!$this->isLoggedIn())
        {
            // save the requested page to return to after logging in
            $url = $_SERVER['REQUEST_URI'];
            if(!empty($url)){
                $_SESSION['return_to'] = $url;
            }
            redirect('/login.php');
        }
    }


    /**
     * If the user is logged in, the is not allowed here
     */
    public function requireGuest()
    {
        if($this->isLoggedIn())
        {
            redirect('admin/index.php');
        }
    }




}
