<?php
class Application_Form_Banner extends Zend_Form
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/banner.ini',
            'banner'
        );
        $this->setConfig($config->banner);
    }
    
    public function setBannerId($id)
    {
        $this->addElement(
            'hidden',
            'bannerId',
            array(
                'value' => $id,
                'decorators' => array(
                    'viewHelper'
                )
            )
        );
    }
    
    public function getLoadedBanner() {
    	if (null == $this->_loadedBanner) {
    		throw new Exception('No se indico previamente ningun banner con el metodo populateWithBannerId');
    	}
    	return $this->_loadedBanner;
    }
    
    private $_loadedBanner = null;
    
    public function populateWithBannerId($id) {
        $this->_loadedBanner = Banners::findById($id);
        
        $this->getElement('title')->setValue($this->_loadedBanner['title']);
        $this->getElement('position')->setValue($this->_loadedBanner['position']);
        $this->getElement('href')->setValue($this->_loadedBanner['href']);
        $this->getElement('active')->setValue($this->_loadedBanner['active']);
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
    
    function isValid($data) {
        if ($_FILES['banner']['error'] != 0) {
            $this->getElement('banner')->removeValidator('Count');
        }
        return parent::isValid($data);
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
                'onBeforeSubmit' => new Zend_Json_Expr(
                    <<<afterDefaultValid
function(element, errorMessages) {
    $.post(
        '/admin/ajax/do/agregarPoliticaPublica',
        $('#politicaPublicaFormTag').serialize(),
        function (response) {
            var id = response.politicaPublicaId;
            var uploader = $('#uploader').pluploadQueue();
            uploader.start();
        }
    );
}
afterDefaultValid
                ),
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