<?php

/**
 * Initialisation
 */
defined("DS") || define("DS", DIRECTORY_SEPARATOR);

// Root Path
defined("ROOT_PATH") || define("ROOT_PATH", realpath(dirname(__FILE__).DS."..".DS));

// Classes Folder
defined("CLASSES_DIR") || define("CLASSES_DIR", ROOT_PATH.DS."classes");




/**
 * Autoloader
 *
 * @param string $className The name of the class
 * @return void
 */
function caAutoloader($className){

    $file = CLASSES_DIR.DS.$className.'.class.php';
    if(file_exists($file)){
        require_once($file);
    }
}

// Register Autoload function
spl_autoload_register('caAutoloader');