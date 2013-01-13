<?php
class My_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        return $this->_initDoctrine();
    }
    
    protected function _initDoctrine()
    {
        $options = $this->getOptions();
        //Load the autoloader
        Zend_Loader_Autoloader::getInstance()->registerNamespace('Doctrine')->
                pushAutoloader(array('Doctrine', 'autoload'));
        $manager = Doctrine_Manager::getInstance();
        foreach ($options['attr'] as $key => $val) {
            $manager->setAttribute(constant("Doctrine::$key"), $val);
        }
        $conn = Doctrine_Manager::connection($options['dsn'], 'doctrine');
        $conn->setCharset('utf8');
        
        Doctrine::loadModels($options["models_path"]);
        
        return $conn;
    }
}