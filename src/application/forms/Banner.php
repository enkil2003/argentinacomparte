<?php
class Application_Form_Banner extends Application_Form_AdminAbstract
{
    private $_loadedBanner = null;
    
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
    
    public function populateWithBannerId($id) {
        $this->_loadedBanner = Banners::findById($id);
        
        $this->getElement('title')->setValue($this->_loadedBanner['title']);
        $this->getElement('position')->setValue($this->_loadedBanner['position']);
        $this->getElement('href')->setValue($this->_loadedBanner['href']);
        $this->getElement('active')->setValue($this->_loadedBanner['active']);
    }
    
    function isValid($data) {
        if ($_FILES['banner']['error'] != 0) {
            $this->getElement('banner')->removeValidator('Count');
        }
        return parent::isValid($data);
    }
}