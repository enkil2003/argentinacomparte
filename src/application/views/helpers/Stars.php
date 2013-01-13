<?php
class Application_View_Helper_Stars extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/stars.phtml';
    
    public function stars($data)
    {
        $this->_data = $data;
        return $this;
    }
}
