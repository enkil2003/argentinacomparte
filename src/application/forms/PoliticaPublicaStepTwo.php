<?php
class Application_Form_PoliticaPublicaStepTwo extends Application_Form_AdminAbstract
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
            'stepTwo'
        );
        $this->setConfig($config->publicPolitics);
    }
    
    public function setModify()
    {
        $this->getElement('publicPoliticsSubmit')->setLabel('Modificar galería');
        $button = new Zend_Form_Element_Submit('geoloc');
        $button->setOptions(
            array(
                'class' => 'btn-primary btn-large',
                'label' => 'Ir a geolocalización',
                'decorators' => array(
                    'videHelper' => 'viewHelper',
                )
            )
        );
        $this->addElement($button);
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
}
