<?php
class Application_View_Helper_Accordion extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/accordion.phtml';
    
    public function accordion($data)
    {
        $this->_data = $data;
        return $this;
    }
}
