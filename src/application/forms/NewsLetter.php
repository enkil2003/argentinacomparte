<?php
class Application_Form_NewsLetter extends Application_Form_AdminAbstract
{
    public function init() 
    {
        $config = new \Zend_Config_Ini(
                APPLICATION_PATH . '/configs/forms/newsletter.ini',
                'newsletter'
        );
        $this->setConfig($config->newsletter);
    }
}