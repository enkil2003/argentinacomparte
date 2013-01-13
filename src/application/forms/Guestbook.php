<?php
class Application_Form_Guestbook extends Zend_Form
{
    public function init() 
    {
        $config = new \Zend_Config_Ini(
                APPLICATION_PATH . '/configs/forms/guestbook.ini',
                'guestbook'
        );
        $this->setConfig($config->guestbook);
    }
    
}