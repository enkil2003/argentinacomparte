<?php
class Application_Form_EditarPoliticaPublicaStepOne extends Zend_Form
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $this->setAttrib('id', 'selectPublicPoliticsCategory');
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/politicaPublica.ini',
            'editarPoliticaPublicaStepOne'
        );
        $this->setConfig($config->stepOne);
        $this->_populateSelectWithCategories();
        $this->getElement('publicPolitic')->addMultiOption(-1, 'Seleccione una política pública');
        $this->_setClasses();
//        $this->_configureJsDecorator();
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
    
    private $_loadedCategories = NULL;
    private function _populateSelectWithCategories($checked = NULL)
    {
        $select = $this->getElement('category');
        if (NULL === $this->_loadedCategories) {
            $categoryModel = new Category();
            $this->_loadedCategories = $categoryModel->getAll();
        } 
        $select->addMultiOption('-1', 'Seleccione una categoría');
        $select->addMultiOption(0, 'Sin categoría');
        $categoryModel = new Category();
        foreach ($this->_loadedCategories as $category) {
            $select->addMultiOption($category['id'], $category['name']);
        }
        if (NULL !== $checked && in_array($checked, $this->_loadedCategories)) {
            $select->setValue($checked);
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