<?php
class Zend_View_Helper_PublicPoliticsSlider extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/publicPoliticsSlider.phtml';
    
    public function publicPoliticsSlider($data)
    {
        $this->_data = $data;
        return $this;
    }
}