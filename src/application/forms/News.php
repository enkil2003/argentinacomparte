<?php
class Application_Form_News extends Zend_Form
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/news.ini',
            'stepOne'
        );
        $this->setConfig($config->news);
        $this->_populateSelectWithPublicPolitics();
        $this->_setClasses();
        
        $select2 = $this->getElement('copy');
        $select2->addDecorator('contador');
        
//        $select = $this->getElement('preferentialCategory');
//        $select->addMultiOption(0, 'Sin categoría');
       $this->_configureJsDecorator();
    }
    
    public function setNewsId($id)
    {
        $this->addElement(
            'hidden',
            'newsId',
            array(
                'value' => $id,
                'decorators' => array(
                    'viewHelper'
                )
            )
        );
    }
    
    private $_loadedNews = null;
    
    /**
     * Loads a public politic into the form for edition
     * @param int $id
     * @return void
     */
    public function populateWithNewsId($id)
    {
        $newsModel = new News();
        $this->_loadedNews = $newsModel->getNewsFromId($id);
        $categories = array();
        if (isset($this->_loadedNews['NewsHasCategory']) && count($this->_loadedNews['NewsHasCategory'])) {
            foreach($this->_loadedNews['NewsHasCategory'] as $category) {
                $categories[] = $category['category_id'];
            }
        }
        $this->getElement('pp')->setValue($this->_loadedNews['news_id']);
        list($year, $month, $day) = explode('-', $this->_loadedNews['creation_date']);
        $date = "{$day}/{$month}/{$year}";
        $this->getElement('title')->setValue($this->_loadedNews['title']);
        $this->getElement('copy')->setValue($this->_loadedNews['copy']);
        $this->getElement('title')->setValue($this->_loadedNews['title']);
        $this->getElement('body')->setValue($this->_loadedNews['body']);
        $this->getElement('youtube')->setValue($this->_loadedNews['youtube']);
        $this->getElement('date')->setValue($date);
        $this->getElement('mintit')->setValue($this->_loadedNews['mintit']);
//        $this->getElement('category')->setValue($categories);
        $this->getElement('preferentialCategory')->setValue($this->_loadedNews['preferential_category']);
        $this->getElement('active')->setValue($this->_loadedNews['active']);
        
        $modifyPluploadDecorator = new My_Form_Decorator_ModifyPlupload();
        $modifyPluploadDecorator->setFolder($this->_loadedNews['id']);
        $modifyPluploadDecorator->setImages($this->_getLoadedImages());
        $this->getElement('uploader')->addDecorator($modifyPluploadDecorator);
    }
    
    private function _getLoadedImages()
    {
        if (!isset($this->_loadedNews['Images']) || !is_array($this->_loadedNews['Images'])) {
            return array();
        }
        $images = array();
        foreach($this->_loadedNews['Images'] as $image) {
            $images[] = $image['name'];
        }
        return $images;
    }
    
    private function _populateSelectWithPublicPolitics()
    {
        $publicPoliticsRecords = News::getAllPublicPolitics();
        $publicPoliticsElement = $this->getElement('pp');
        foreach($publicPoliticsRecords as $publicPolitic) {
        	if ($publicPolitic['active'] == 0){
        		$publicPolitic['title'] .= " - (borrador)";
        	}
            $publicPoliticsElement->addMultiOption(
                $publicPolitic['id'],
                $publicPolitic['title']
            );
        }
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
function(form, settings) {
    $.scrollTo($($('.control-group .errors')[0]).parent(), 500, {offset: {top: -70}});
}
onValidationFails
                )
            )
        );
    }
}