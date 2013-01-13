<?php
// test.php

require_once('Bootstraploc.php');
$schema_path = APPLICATION_PATH.'/configs';
$model_path  = APPLICATION_PATH.'/models';
Doctrine_Core::generateYamlFromDb($schema_path);
Doctrine_Core::generateModelsFromYaml($schema_path, $model_path, array(
        'baseClassesDirectory' => 'base'
    ));

?>
