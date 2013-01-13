<?php
require_once 'My/Application/Resource/HeadFileAbstract.php';
class My_Application_Resource_Stylesheets extends My_Application_Resource_HeadFileAbstract
{
    public function init()
    {
        return $this->_injectCSS();
    }
    
    /**
     * Injects Css files into document's header
     * @return Zend_View
     */
    protected function _injectCSS()
    {
        $options = $this->getOptions();
        if (isset($options['styleSheets'])) {
            if (is_array($options['styleSheets'])) {
                foreach ($options['styleSheets'] as $stylesheet) {
//                    if(!$this->_isMobile() && $stylesheet['mobile']) {
//                        continue;
//                    }
                    $media = isset($stylesheet['media'])? $stylesheet['media']:'all';
                    $conditional = isset($stylesheet['ie'])? $stylesheet['ie']:'';
                    $this->_view->headLink()->appendStylesheet($options['cdn'] . $stylesheet['file'], $media, $conditional);
                }
            } else {
                $this->_view->headLink()->appendStylesheet($options['cdn'] . $options['styleSheets']);
            }
        }
        return $this->_view;
    }
}
