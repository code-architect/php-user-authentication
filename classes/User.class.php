<?php


class User
{
    protected static $db = null ;
    public $errors;

//    public function __construct()
//    {
//        self::$db = Database::get_instance();
//    }



    public static function signUp($data)
    {
        // Create a new user model and set the attributes
        $user = new static();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        if($user->isValid()) {

            try {
                $db = Database::get_instance();

                $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
                $stmt->bindParam(':name', $data['name']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':password', Hash::make($data['password']));

                $stmt->execute();

            } catch (PDOException $exception) {

                // Log the exception message
                error_log($exception->getMessage());
            }
        }
        return $user;
    }


    /**
     * Check if submitted fields are valid or not
     */
    public function isValid()
    {
        $this->errors = [];

        // name
        if($this->name == ''){
            $this->errors['name'] = 'Please Enter a valid name';
        }

        //email
        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false){
            $this->errors['email'] = 'Please Enter Valid Email Address';
        }

        // check if email exists int the database or not
        if($this->emailExists($this->email)){
            $this->errors['email'] = 'This Email is already taken';
        }

        //check the password length
        if(strlen($this->password) <6){
            $this->errors['password'] = 'Please Enter a password longer then 7 character';
        }

        return empty($this->errors);

    }


    /**
     * Check if email exists in the database or not
     * @param $email
     * @return bool
     */
    public function emailExists($email) {
        try {

            $db = Database::get_instance();

            $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $this->email]);

            $rowCount = $stmt->fetchColumn();
            return $rowCount == 1;

        } catch(PDOException $exception) {

            error_log($exception->getMessage());
            return false;
        }
    }



    /**
     * Authenticate a user by email and password
     *
     * @param string $email     Email address
     * @param string $password  Password
     * @return mixed            User object if authenticated correctly, null otherwise
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user !== null) {

            // Check the hashed password stored in the user record matches the supplied password
            if (Hash::check($password, $user->password)) {
                return $user;
            }
        }
    }



    /**
     * Find the user with the specified email address
     *
     * @param string $email  email address
     * @return mixed         User object if found, null otherwise
     */
    public static function findByEmail($email)
    {
        try {

            $db = Database::get_instance();

            $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetchObject('User');

            if ($user !== false) {
                return $user;
            }

        } catch(PDOException $exception) {

            error_log($exception->getMessage());
        }
    }



    /**
     * Find the user with the specified ID
     *
     * @param string $id  ID
     * @return mixed      User object if found, null otherwise
     */
    public static function findByID($id)
    {
        try {

            $db = Database::get_instance();

            $stmt = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetchObject('User');

            if ($user !== false) {
                return $user;
            }

        } catch(PDOException $exception) {

            error_log($exception->getMessage());
        }
    }



    public function rememberLogin($expiry_date)
    {
        // generate a unique token
        $token = uniqid($this->email, true);
        try{
            $db = Database::get_instance();

            $stmt = $db->prepare('INSERT INTO remembered_logins (token, user_id, expires_at) VALUES (:token, :user_id, :expires_at)');
            $stmt->bindParam(':token', sha1($token));
            $stmt->bindParam(':user_id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':expires_at', date('Y-m-d H:i:s', $expiry_date));

            if($stmt->rowCount == 1){
                return $token;
            }

        }catch (PDOException $e){
            // Log the detailed exception
            error_log($e->getMessage());
        }
        return false;
    }




    public static function findByRememberToken($token)
    {
        try{
            $db = Database::get_instance();

            $stmt = $db->prepare('Select u.* from users u JOIN remembered_logins r ON u.id = r.user_id WHERE token = :token');
            $stmt->execute([':token' => $token]);
            $user = $stmt->fetchObject('User');

            if($user !== false)
            {
                return $user;
            }
        }catch (PDOException $e){
            error_log($e->getMessage());
        }
    }

    /**
     * Forget the login based on the token value
     *
     * @param string $token  Remember token
     * @return void
     */
    public function forgetLogin($token)
    {
        if ($token !== null) {

            try {

                $db = Database::getInstance();

                $stmt = $db->prepare('DELETE FROM remembered_logins WHERE token = :token');
                $stmt->bindParam(':token', $token);
                $stmt->execute();

            } catch(PDOException $exception) {

                // Log the detailed exception
                error_log($exception->getMessage());
            }
        }
    }





}