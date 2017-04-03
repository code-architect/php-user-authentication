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





}