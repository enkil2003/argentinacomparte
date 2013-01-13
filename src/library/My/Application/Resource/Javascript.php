<?php
require_once 'My/Application/Resource/HeadFileAbstract.php';
class My_Application_Resource_Javascript extends My_Application_Resource_HeadFileAbstract
{
    public function init()
    {
        return $this->_injectJs();
    }

    /**
     * Injects Css files into document's header
     * @return Zend_View
     */
    protected function _injectJs()
    {
        $options = $this->getOptions();
        if(isset($options['script'])) {
            if(is_array($options['script'])) {
                foreach($options['script'] as $script) {
                    $this->_view->headScript()->appendFile($options['cdn'] . $script);
                }
            } else {
                $this->_view->headScript()->appendFile($options['script']);
            }
        }
        return $this->_view;
    }
}
