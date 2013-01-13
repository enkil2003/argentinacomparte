<?php
abstract class Application_Form_AdminAbstract extends Zend_Form
{
    protected $_onAfterInvalidElement = <<<SCRIPT
function(element) {
    element.parent().removeClass('success').addClass('error');
    var errorContainer = element.parent().find('ul');
    errorContainer.addClass('help-inline label label-important');
}
SCRIPT;
    
    protected $_onAfterValidElement = <<<SCRIPT
function(element) {
    element.parent().removeClass('error').addClass('success');
}
SCRIPT;
    
    protected $_onValidationFails = <<<SCRIPT
function(form, settings) {
    $.scrollTo($($('.control-group .errors')[0]).parent(), 500, {offset: {top: -70}});
}
SCRIPT;
    
    protected $_onBeforeSubmit = <<<SCRIPT
function() {
    //
}
SCRIPT;
    
    /**
     * Prepares base clases to use with twitter's bootstrap css
     * @return void
     */
    protected function _setClasses()
    {
        foreach($this->getElements() as $element) {
            $decorator = $element->getDecorator('HtmlTag');
            if (!method_exists($decorator, 'setOption')) {
                continue;
            }
            $decorator->setOption('class', 'control-group');
        }
    }
    
    public function loadDefaultDecorators()
    {
        parent::loadDefaultDecorators();
        $this->_setClasses();
        $this->_configureJsDecorator();
    }
    
    
    public function setOnAfterInvalidElement($script)
    {
        $this->_onAfterInvalidElement = $script;
        return $this;
    }
    
    public function setOnAfterValidElement($script)
    {
        $this->_onAfterValidElement = $script;
        return $this;
    }
    
    public function setOnValidationFails($script = '')
    {
        $this->_onValidationFails = $script;
        return $this;
    }

    function getOnAfterInvalidElement()
    {
    	return $this->_onAfterInvalidElement;
    }

    function getOnAfterValidElement()
    {
        return $this->_onAfterValidElement;
    }

    function getOnValidationFails()
    {
    	return $this->_onValidationFails;
    }

    function getOnBeforeSubmit()
    {
        return $this->_onBeforeSubmit;
    }
    
    function setOnBeforeSubmit($script)
    {
        $this->_onBeforeSubmit = $script;
        return $this;
    }
    
    /**
     * Configures the JsAutoValidation decorator for custom behavior
     * return void;
     */
    protected function _configureJsDecorator()
    {
        $jsvalidation = $this->getDecorator('JsAutoValidation');
        
        $jsvalidation->setOption(
            'validatorOptions', array(
                'onAfterInvalidElement' => new Zend_Json_Expr($this->getOnAfterInvalidElement()),
                'onAfterValidElement' => new Zend_Json_Expr($this->getOnAfterValidElement()),
                'onValidationFails' => new Zend_Json_Expr($this->getOnValidationFails()),
                'onBeforeSubmit' => new Zend_Json_Expr($this->getOnBeforeSubmit()),
            )
        );
    }
}