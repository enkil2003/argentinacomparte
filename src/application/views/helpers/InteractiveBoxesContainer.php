<?php
class Application_View_Helper_InteractiveBoxesContainer extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/interactiveBoxesContainer.phtml';
    
    public function interactiveBoxesContainer($data)
    {
        $this->_data = $data;
        return $this;
    }
}
