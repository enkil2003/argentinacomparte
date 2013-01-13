<?php
class Application_View_Helper_ViewHelperContainer extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/accordion.phtml';
    
    public function viewHelperContainer($data)
    {
        $this->_data = $data;
        return $this;
    }
}
