<?php


class User
{
    protected static $db = null ;

    public function __construct()
    {
        self::$db = Database::get_instance();
    }



    public static function signUp($data)
    {
        try {
            $db = Database::get_instance();

            $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', Hash::make($data['password']));

            $stmt->execute();

        } catch(PDOException $exception) {

            // Log the exception message
            error_log($exception->getMessage());
        }
    }





}