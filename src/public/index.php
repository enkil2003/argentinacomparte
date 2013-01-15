<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', (getenv('PUBLIC_PATH')
    ? getenv('PUBLIC_PATH')
    : realpath(APPLICATION_PATH . '/../public')));
    
defined('APPLICATION_TMP_DIR')
    || define('APPLICATION_TMP_DIR', (getenv('APPLICATION_TMP_DIR')
    ? getenv('APPLICATION_TMP_DIR')
    : realpath(APPLICATION_PATH . '/../public/uploads/tmp')));
    
/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();