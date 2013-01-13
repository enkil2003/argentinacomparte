<?php
require_once 'My/Application/Resource/ViewAbstract.php';
class My_Application_Resource_Blueprint extends My_Application_Resource_ViewAbstract
{
    public function init()
    {
        return $this->_injectBlueprint();
    }

    /**
     * Injects Blueprint css in the document's header
     * @return Zend_View
     */
    protected function _injectBlueprint()
    {
        $options = $this->getOptions();
        $cdn = $options['cdn'] ? $options['cdn']:''; 
        $this->_view->headLink()->appendStylesheet($cdn.'/css/blueprint/screen.css', 'all');
        $this->_view->headLink()->appendStylesheet($cdn.'/css/blueprint/print.css', 'print');
        $this->_view->headLink()->appendStylesheet($cdn.'/css/blueprint/ie.css', 'screen', 'IE');
        return $this->_view;
    }
}