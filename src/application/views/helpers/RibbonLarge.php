<?php
class Application_View_Helper_RibbonLarge extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/ribbonLarge.phtml';
    
    public function ribbonLarge($data)
    {
        $this->_data = $data;
        return $this;
    }
}