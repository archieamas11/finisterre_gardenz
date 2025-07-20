<?php
//define the core paths
//Define them as absolute paths to make sure that require_once works as expected

//DIRECTORY_SEPARATOR is a PHP Pre-defined constants:
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// More robust SITE_ROOT definition using __DIR__ to refer to the parent directory of the current file's directory (i.e., cemeterease/)
defined('SITE_ROOT') ? null : define ('SITE_ROOT', dirname(__DIR__));

defined('LIB_PATH') ? null : define ('LIB_PATH',SITE_ROOT.DS.'include');

// Load Composer's autoloader early
require_once(SITE_ROOT.DS."vendor/autoload.php");
require_once(LIB_PATH.DS."config.php");
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."auth_session.php");
require_once(LIB_PATH.DS."cloudinary_config.php");
?>