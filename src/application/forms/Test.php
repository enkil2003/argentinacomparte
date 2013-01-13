<?php
class Application_Form_Test extends Zend_Form
{
    private $_loadedCategories = NULL;
    private $_loadedPublicPolitic = NULL;
    
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/test.ini',
            'politicaPublica1'
        );
        $this->setConfig($config->politicaPublica1);
        $this->_setClasses();
        $this->_configureJsDecorator();
    }
    
    /**
     * Prepares base clases to use with twitter's bootstrap css
     * @return void
     */
    private function _setClasses()
    {
        foreach($this->getElements() as $element) {
            $decorator = $element->getDecorator('HtmlTag');
            if (!method_exists($decorator, 'setOption')) {
                continue;
            }
            $decorator->setOption('class', 'control-group');
        }
    }
    
    private function _configureJsDecorator()
    {
        $jsvalidation = $this->getDecorator('JsAutoValidation');
        
        $jsvalidation->setOption(
            'validatorOptions', array(
                'onAfterInvalidElement' => new Zend_Json_Expr(
                    <<<onAfterInvalidElement
function(element) {
    element.parent().removeClass('success').addClass('error');
    var errorContainer = element.parent().find('ul');
    errorContainer.addClass('help-inline label label-important');
}
onAfterInvalidElement
                ),
                'onAfterValidElement' => new Zend_Json_Expr(
                    <<<onAfterValidElement
function(element) {
    element.parent().removeClass('error').addClass('success');
}
onAfterValidElement
                )
            )
        );
    }
}
