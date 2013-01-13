<?php
abstract class My_Application_Resource_ViewAbstract extends Zend_Application_Resource_ResourceAbstract
{
    protected $_view = null;
    
    /**
     * Sets the view as a property of the class
     * @see Zend_Application_Resource_ResourceAbstract::__construct
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $bs = $this->getBootstrap();
        $bs->bootstrap('View');
        $this->_view = $bs->getResource('view');
    }
}