<?php
namespace ZendMax\Application\Resource;

/** ZendMax_Application_Resource_ViewAbstract */
require_once 'ZendMax/Application/Resource/ViewAbstract.php';

/**
 * \ZendMax\Application\Resource\Headlink
 *
 * @uses       \ZendMax\Application\Resource\ViewAbstract
 * @package    \ZendMax\Application
 * @subpackage Resource
 */
class Headlink
    extends ViewAbstract
{
    /**
     * Initialises the resource
     * @see \Zend_Application_Resource_Resource::init()
     * @return void
     */
    public function init()
    {
        $this->_loadLinksFromIni();
    }
    
    /**
     * Loads links from application.ini
     * @return void
     */
    protected function _loadLinksFromIni()
    {
        $options = $this->getOptions();
        if (!isset($options) && !count($options)) {
            return;
        }
        foreach ($options as $link) {
            $this->_view->headLink($link);
        }
    }
}