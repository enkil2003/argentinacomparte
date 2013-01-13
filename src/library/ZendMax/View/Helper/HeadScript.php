<?php
namespace ZendMax\View\Helper;
/** Zend_View_Helper_Placeholder_Container_Standalone */
require_once 'Zend/View/Helper/Placeholder/Container/Standalone.php';

/**
 * Helper for setting and retrieving script elements for HTML head section
 *
 * @uses       \Zend_View_Helper_Placeholder_Container_Standalone
 * @package    \ZendMax\View
 * @subpackage Helper
 */
class HeadScript extends ScriptLoaderAbstract
{
    /**
     * Registry key for placeholder
     * @var string
     */
    protected $_regKey = 'ZendMax_View_Helper_HeadScript';
}
