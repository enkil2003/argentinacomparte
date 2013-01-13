<?php
class Application_Form_PoliticaPublica extends Zend_Form
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
            APPLICATION_PATH . '/configs/forms/politicaPublica.ini',
            'publicPolitics'
        );
        $this->setConfig($config->publicPolitics);
        $this->_populateSelectWithCategories();
        $this->_setClasses();
        $select = $this->getElement('preferentialCategory');
        //$select->addMultiOption(0, 'Sin categoría');
        $this->_configureJsDecorator();
        
        $select2 = $this->getElement('copy');
        $select2->addDecorator('contador',array());
    }
    
    public function setPublicPoliticsId($id) {
        $this->addElement(
            'hidden',
            'publicPoliticId',
            array(
                'value' => $id,
                'decorators' => array(
                    'viewHelper'
                )
            )
        );
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
    
    private function _populateSelectWithCategories($checked = NULL)
    {
        $select = $this->getElement('category');
        if (NULL === $this->_loadedCategories) {
            $categoryModel = new Category();
            $this->_loadedCategories = $categoryModel->getAll();
        } 
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
                    <<<onAfterInvalidElement
function(element) {
    element.parent().removeClass('error').addClass('success');
}
onAfterInvalidElement
                ),
                'onValidationFails' => new Zend_Json_Expr(
                    <<<onValidationFails
function() {
    $.scrollTo($($('.control-group .errors')[0]).parent(), 500, {offset: {top: -70}});
}
onValidationFails
                ),
                'onBeforeSubmit' => new Zend_Json_Expr(
                    <<<onValidationPass
function() {
    submitPublicPolitics();
}
onValidationPass
                ),
            )
        );
    }
    
    /**
     * Loads a public politic into the form for edition
     * @param int $id
     * @return void
     */
    public function populateWithPublicPoliticId($publicPolitic)
    {
        $this->_loadedPublicPolitic = $publicPolitic;
        
        $categories = array();
        if (isset($this->_loadedPublicPolitic['NewsHasCategory']) && count($this->_loadedPublicPolitic['NewsHasCategory'])) {
            foreach($this->_loadedPublicPolitic['NewsHasCategory'] as $category) {
                $categories[] = $category['category_id'];
            }
        }
        list($year, $month, $day) = explode('-', $this->_loadedPublicPolitic['creation_date']);
        $date = "{$day}/{$month}/{$year}";
        $this->getElement('title')->setValue($this->_loadedPublicPolitic['title']);
        $this->getElement('copy')->setValue($this->_loadedPublicPolitic['copy']);
        $this->getElement('title')->setValue($this->_loadedPublicPolitic['title']);
        $this->getElement('body')->setValue($this->_loadedPublicPolitic['body']);
        $this->getElement('youtube')->setValue($this->_loadedPublicPolitic['youtube']);
        $this->getElement('date')->setValue($date);
        $this->getElement('category')->setValue($categories);
        $this->getElement('preferentialCategory')->setValue($this->_loadedPublicPolitic['preferential_category']);
        $this->getElement('active')->setValue($this->_loadedPublicPolitic['active']);
        
        $modifyPluploadDecorator = new My_Form_Decorator_ModifyPlupload();
        $modifyPluploadDecorator->setFolder($this->_loadedPublicPolitic['id']);
        $modifyPluploadDecorator->setImages($this->_getLoadedImages());
        $this->getElement('uploader')->addDecorator($modifyPluploadDecorator);
    }
    
    private function _getLoadedImages()
    {
        if (!isset($this->_loadedPublicPolitic['Images']) || !is_array($this->_loadedPublicPolitic['Images'])) {
            return array();
        }
        $images = array();
        foreach($this->_loadedPublicPolitic['Images'] as $image) {
            $images[] = $image['name'];
        }
        return $images;
    }
}
