<?php
class Application_Form_Category extends Zend_Form
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/category.ini',
            'category'
        );
        $this->setConfig($config->category);
        $this->_populateSelectWithCategories();
        $this->_setClasses();
        $select = $this->getElement('preferentialCategory');
        //$select->addMultiOption(0, 'Sin categoría');
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
    
    private $_loadedCategories = NULL;
    private function _populateSelectWithCategories($checked = NULL)
    {
        $select = $this->getElement('category');
        $categoryModel = new Category();
        if (NULL === $this->_loadedCategories) {
            $this->_loadedCategories = $categoryModel->getAll();
        } 
        //$select->addMultiOption(0, 'Sin categoría');
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