<?php
namespace Form;
class Register extends \Zend_Form
{
    /**
     * Creates the register form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new \Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/register.ini',
            'register'
        );
        $this->setConfig($config->register);
    }
}