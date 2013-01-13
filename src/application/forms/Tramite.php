<?php
class Application_Form_Tramite extends Zend_Form
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
    
    public function setTramiteId($id)
    {
        $this->addElement(
            'hidden',
            'tramiteId',
            array(
                'value' => $id,
                'decorators' => array(
                    'viewHelper'
                )
            )
        );
    }
    
    private $_loadedTramite = null;
    
    /**
     * Loads a public politic into the form for edition
     * @param int $id
     * @return void
     */
    public function populateWithTramiteId($id)
    {
        $tramiteModel = new Tramite();
        $this->_loadedTramite = Tramite::findById($id);
        
        list($year, $month, $day) = explode('-', $this->_loadedTramite['creation_date']);
        $date = "{$day}/{$month}/{$year}";
        $this->getElement('title')->setValue($this->_loadedTramite['title']);
        $this->getElement('body')->setValue($this->_loadedTramite['body']);
        $this->getElement('youtube')->setValue($this->_loadedTramite['youtube']);
        $this->getElement('date')->setValue($date);
        $this->getElement('active')->setValue($this->_loadedTramite['active']);
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
    
    /**
     * Configures the JsAutoValidation decorator for custom behavior
     * return void;
     */
    private function _configureJsDecorator()
    {
        $jsvalidation = $this->getDecorator('JsAutoValidation');
        
        $jsvalidation->setOption(
            'validatorOptions', array(
                'onAfterInvalidElement' => new Zend_Json_Expr(
                    <<<onAfterInvalidElement
function(element) {
    element.parent().removeClass('success').addClass('error');
}
onAfterInvalidElement
                ),
                'onAfterValidElement' => new Zend_Json_Expr(
                    <<<onAfterInvalidElement
function(element) {
    element.parent().removeClass('error').addClass('success');
}
onAfterInvalidElement
                )
            )
        );
    }
}