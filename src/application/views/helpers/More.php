<?php
class Application_View_Helper_More extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/more.phtml';
    
    public function more($data)
    {
        if (isset($data['slug'])) {
            $filter = new My_Filter_Slug();
            $data['slug'] = $filter->filter($data['slug']);
        }
        $this->_data = $data;
        return $this;
    }
}