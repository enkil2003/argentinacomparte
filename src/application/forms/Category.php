<?php
class Application_Form_Category extends Application_Form_AdminAbstract
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
        $select = $this->getElement('preferentialCategory');
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
}
