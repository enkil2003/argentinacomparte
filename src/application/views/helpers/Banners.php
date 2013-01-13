<?php
class Application_View_Helper_Banners extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/banners.phtml';
    
    public function banners($data)
    {
        $this->_data = $data;
        return $this;
    }
}
