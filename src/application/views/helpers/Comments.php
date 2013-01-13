<?php
class Application_View_Helper_Comments extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/comments.phtml';
    
    public function comments($data)
    {
        $this->_data = $data;
        return $this;
    }
}
