<?php
class Application_Form_PoliticaPublicaStepOne extends Application_Form_AdminAbstract
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
            'stepOne'
        );
        $this->setConfig($config->publicPolitics);
        $this->_populateSelectWithCategories();
        $select = $this->getElement('preferentialCategory');
        $select2 = $this->getElement('copy');
        $select2->addDecorator('contador',array());
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
