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
        
        $this->_configureJsDecorator();
    }
    
    /**
     * Configures the JsAutoValidation decorator for custom behavior
     * return void;
     */
    private function _configureJsDecorator()
    {
        $jsvalidation = $this->getDecorator('JsAutoValidation');
        $jsvalidation->setOption(
            'validatorOptions', array(
                'afterDefaultInvalid' => new \Zend_Json_Expr(
                    <<<afterDefaultInvalid
function(element, errorMessages) {
    element.parent().addClass('error');
}

afterDefaultInvalid
                ),
                'afterDefaultValid' => new \Zend_Json_Expr(
                    <<<afterDefaultValid
function(element, errorMessages) {
    element.parent().removeClass('error');
}

afterDefaultValid
                )
            )
        );
    }
}