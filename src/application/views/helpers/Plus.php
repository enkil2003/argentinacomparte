<?php
class Application_View_Helper_Plus extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/plus.phtml';
    
    public function plus()
    {
        return $this;
    }
}
