<?php
require_once 'My/Application/Resource/ViewAbstract.php';
class My_Application_Resource_Jquery extends My_Application_Resource_ViewAbstract
{
    public function init()
    {
        return $this->_injectJquery();
    }

    /**
     * Injects Jquery css in the document's header
     * @return Zend_View
     */
    protected function _injectJquery()
    {
        $options = $this->getOptions();
        $this->_view->headScript()->appendFile($options['cdn'] . '/js/jquery/jquery-' . $options['version'] . '.js');
        if ($options['plugins']) {
            foreach ($options['plugins']['plugin'] as $plugin) {
                $this->_view->headScript()->appendFile($options['cdn'] . $options['plugins']['basedir'] . '/' . $plugin);
            }
        }
        return $this->_view;
    }
}