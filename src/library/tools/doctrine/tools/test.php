<?php
include_once ('Bootstrap.php');

$model_path  = APPLICATION_PATH.'/models';

spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine', 'modelsAutoload'));

 Doctrine::loadModels($model_path);

$q = Doctrine_Query::create()
->from('User u');


var_dump($q);
