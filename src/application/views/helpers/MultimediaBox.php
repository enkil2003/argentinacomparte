<?php
class Application_View_Helper_MultimediaBox extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/multimediaBox.phtml';
    
    public function multimediaBox($data)
    {
        $data['categoryClass'] = $this->view->categoryClass;
        $data['categoryBackgroundClass'] = $this->view->categoryBackgroundClass;
        $this->_data = $data;
        return $this;
    }
}
