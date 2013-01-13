<?php
class Application_View_Helper_NavigationBar extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/navigationBar.phtml';
    
    public function navigationBar($data)
    {
//        $category = new Category();
//        $this->_data = $category->getAll();
        $this->_data = $data;
        return $this;
    }
}
