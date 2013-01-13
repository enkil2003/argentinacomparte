<?php
require_once 'My/Application/Resource/ViewAbstract.php';
class My_Application_Resource_Jqueryui extends My_Application_Resource_ViewAbstract
{
    public function init()
    {
        return $this->_injectJqueryui();
    }

    /**
     * Injects JqueryUI css in the document's header
     * @return Zend_View
     */
    protected function _injectJqueryui()
    {
        $options = $this->getOptions();
        $this->_view->headScript()->appendFile(
            $options['cdn'] . '/js/jquery/jquery-ui-' . $options['version'] . '.custom.min.js'
        );
        $this->_view->headLink()->appendStylesheet(
            $options['cdn'] . '/css/' . $options['skin'] . '/jquery-ui-' . $options['version'] . '.custom.css'
        );
        return $this->_view;
    }
}