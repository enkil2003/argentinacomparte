<?php
namespace Form;
class Contact extends \Zend_Form
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new \Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/contact.ini',
            'contact'
        );
        $this->setConfig($config->contact);
    }
}