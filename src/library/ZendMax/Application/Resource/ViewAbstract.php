<?php
/**
 * ZendMax
 *
 * @author - $Author$
 * @date - $Date$
 * 
 * @filesource - $HeadURL$
 * @revision - $Revision$
 * 
 * @LastChangedBy $LastChangedBy $
 * @lastChangedDate - $LastChangedDate$
 * 
 * @lastChangedRevision - lastChangedRevision$
 */
namespace ZendMax\Application\Resource;
/** Zend_Application_Resource_ResourceAbstract */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * \ZendMax_Application_Resource_ViewAbstract
 *
 * @uses       \Zend_Application_Resource_ResourceAbstract
 * @package    \ZendMax\Application
 * @subpackage Resource
 */
abstract class ViewAbstract
    extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * Holds a view instance
     * @var \Zend_View_Abstract
     */
    protected $_view = null;
    
    /**
     * Sets the view as a property of the class
     * @see \Zend_Application_Resource_ResourceAbstract::__construct
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $bs = $this->getBootstrap();
        $bs->bootstrap('View');
        $this->_view = $bs->getResource('view');
    }
}