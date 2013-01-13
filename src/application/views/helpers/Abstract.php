<?php
abstract class Application_View_Helper_Abstract extends Zend_View_Helper_Abstract
{
    protected $_template = null;
    protected $_data = null;
    
    public function toString()
    {
        if (null === $this->_template) {
            throw new Exception('Debe asignar un template al view helper');
        }
        if (null !== $this->_data) {
            return $this->view->partial(
                $this->_template,
                array(
                    'data' => $this->_data
                )
            );
        } else {
            return $this->view->partial(
                $this->_template
            );
        }
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}
