<?php
class Application_Form_Tramite extends Application_Form_AdminAbstract
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/tramite.ini',
            'tramite'
        );
        $this->setConfig($config->tramite);
    }
    
    public function setTramiteId($id)
    {
        $this->addElement(
            'hidden',
            'tramiteId',
            array(
                'value' => $id,
                'decorators' => array(
                    'viewHelper'
                )
            )
        );
    }
    
    private $_loadedTramite = null;
    
    /**
     * Loads a public politic into the form for edition
     * @param int $id
     * @return void
     */
    public function populateWithTramiteId($id)
    {
        $tramiteModel = new Tramite();
        $this->_loadedTramite = Tramite::findById($id);
        
        list($year, $month, $day) = explode('-', $this->_loadedTramite['creation_date']);
        $date = "{$day}/{$month}/{$year}";
        $this->getElement('title')->setValue($this->_loadedTramite['title']);
        $this->getElement('body')->setValue($this->_loadedTramite['body']);
        $this->getElement('youtube')->setValue($this->_loadedTramite['youtube']);
        $this->getElement('date')->setValue($date);
        $this->getElement('active')->setValue($this->_loadedTramite['active']);
    }
}