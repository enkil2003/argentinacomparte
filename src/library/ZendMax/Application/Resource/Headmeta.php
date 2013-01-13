<?php
namespace ZendMax\Application\Resource;

/** ZendMax_Application_Resource_ViewAbstract */
require_once 'ZendMax/Application/Resource/ViewAbstract.php';


/**
 * \ZendMax\Application\Resource\Headmeta
 *
 * @uses       \ZendMax\Application\Resource\ViewAbstract
 * @package    \ZendMax\Application
 * @subpackage Resource
 */
class Headmeta
    extends ViewAbstract
{
    /**
     * Initialises the resource
     * @see Zend_Application_Resource_Resource::init()
     * @return Headmeta
     */
    public function init()
    {
        $this->_loadMetaFromIni();
        return $this;
    }
    
    /**
     * Loads meta from application.ini
     * @return void
     */
    protected function _loadMetaFromIni()
    {
        $options = $this->getOptions();
        if (!isset($options) && !count($options)) {
            return;
        }
        
        if (isset($options['property'])) {
            $this->appendOpenGraphMeta($options['property']);
        }
        
        if (isset($options['appendHttpEquiv'])) {
            $this->setHttpEquivs($options['appendHttpEquiv']);
        }
        
        if (isset($options['name'])) {
            $this->setNames($options['name']);
        }
    }
    
    /**
     * Sets the httpEquivs on headMeta helper
     * @param array $httpEquivs array of arrays with type => content
     * @return Headmeta
     */
    protected function setHttpEquivs($httpEquivs)
    {
        foreach ($httpEquivs as $httpEquiv) {
            $this->_view->headMeta()->appendHttpEquiv(
                $httpEquiv['type'],
                $this->_view->translate($httpEquiv['content'])
            );
        }
        return $this;
    }
    
    /**
     * Sets the meta content in headMeta helper
     * @param array $names array of arrays with type => content
     * @return Headmeta
     */
    protected function setNames($names)
    {
        foreach ($names as $name) {
            $this->_view->headMeta()->appendName(
                $name['type'],
                $this->_view->translate($name['content'])
            );
        }
        return $this;
    }
    
    /**
     * Appends the open graph metatags
     * @param array $properties array of arrays with type => content
     * @return Headmeta
     */
    protected function appendOpenGraphMeta($properties)
    {
        foreach ($properties as $property) {
            $this->_view->openGraph()->appendProperty(
                $property['type'],
                $this->_view->translate($property['content'])
            );
        }
        return $this;
    }
    
}