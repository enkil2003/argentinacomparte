<?php
class Application_Form_DeletePoliticPublics extends Application_Form_AdminAbstract
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
            'deletePublicPolitics'
        );
        $this->setConfig($config->stepOne);
        $this->_populateSelectWithCategories();
        $this->getElement('publicPolitic')->addMultiOption(-1, 'Seleccione una política pública');
    }
    
    public function isValid($data) 
    {
        $news = new News();
        if (is_array($news->findById($data['publicPolitic']))) {
            $this->removeElement('publicPolitic');
            return parent::isValid($data);
        }
        return false;
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
}