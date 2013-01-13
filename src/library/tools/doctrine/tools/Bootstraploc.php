<?php
// bootstrap.php

/**
 * Bootstrap Doctrine.php, register autoloader specify
 * configuration attributes and load models.
 */
define(
    'APPLICATION_PATH', 
    dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/application'
);
//Include paths for Doctrine
set_include_path(
//     PATH_SEPARATOR . dirname(__FILE__).'/../../library'
//    . PATH_SEPARATOR . dirname(__FILE__).'/../../../application/models'
//    . PATH_SEPARATOR . dirname(__FILE__).'/../../../application/models/base'
//    . PATH_SEPARATOR . dirname(__FILE__).'/../../../library/Doctrine'
    APPLICATION_PATH . '/../library:'
    . APPLICATION_PATH . '/models:'
    . APPLICATION_PATH . '/models/base:'
    . APPLICATION_PATH . '/../library/Doctrine-V.1.2.4'
    . PATH_SEPARATOR . get_include_path());

require_once('Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

$manager = Doctrine_Manager::getInstance();
// set the model loading attribute to lazy loading
// @see
$manager->setAttribute(
    Doctrine::ATTR_MODEL_LOADING,
    Doctrine::MODEL_LOADING_CONSERVATIVE
);

spl_autoload_register(array('Doctrine', 'modelsAutoload'));
Doctrine::loadModels(APPLICATION_PATH.'/models');
//Set connection to database

$dbh = "mysql://root:qwerty@127.0.0.1/argentinacomparte";
$conn = Doctrine_Manager::connection($dbh);

