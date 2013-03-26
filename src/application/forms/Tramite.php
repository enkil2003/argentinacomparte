<?php
class Application_Form_Tramite extends Application_Form_AdminAbstract
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/tramite.ini',
            'tramite'
        );
        $this->setConfig($config->tramite);
    }
}