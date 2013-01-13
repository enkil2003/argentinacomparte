<?php
class Application_View_Helper_RibbonShort extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/ribbonShort.phtml';
    
    public function ribbonShort($data)
    {
        $this->_data = $data;
        return $this;
    }
}