<?php


class Hash
{

    private static $_hasher;

    // disallow creating a new obj of the class with new Database()
    private function __construct()
    {
    }

    // disallow cloaning the class
    private function __clone()
    {
    }

    /**
     * Get a hash of a given password
     * @param $text
     * @return string
     */
    public static function make($text)
    {
        return static::_getHasher()->HashPassword($text);
    }



    public static function check($password, $hash)
    {
        return static::_getHasher()->CheckPassword($password, $hash);
    }


    /**
     * Get the singleton password hasher object
     *
     * @return PasswordHash
     */
    private static function _getHasher()
    {
        if (static::$_hasher === NULL) {

            require dirname(dirname(__FILE__)) . '/vendor/PHPass/PasswordHash.php';

            static::$_hasher = new PasswordHash(8, false);
        }

        return static::$_hasher;
    }


}