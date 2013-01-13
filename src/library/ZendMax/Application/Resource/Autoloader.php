<?php
namespace ZendMax\Application\Resource;
class Autoloader extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * @return \Zend_Loader_Autoloader_Resource
     */
    public function init()
    {
        return $this->_injectResources();
    }

    /**
     * Injects Autoloading Resources
     * @return \Zend_Loader_Autoloader_Resource
     */
    protected function _injectResources()
    {
        return new \Zend_Loader_Autoloader_Resource ($this->getOptions());
    }
}

