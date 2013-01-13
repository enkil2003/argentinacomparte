<?php
class Application_View_Helper_ElapsedTime extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/elapsedTime.phtml';
    
    public function elapsedTime($data)
    {
        $this->_data = $data;
        $this->_data['hidden'] = isset($data['hidden'])
            ? $data['hidden']
            : false; 
        $date = new Zend_Date();
//        $now = $date->get();
//        $antes = time() -10000;
//        echo $date->sub($antes);
//        die;
        return $this;
    }
    
    private function setTime(Zend_Date $date)
    {
        $date;
    }
}
