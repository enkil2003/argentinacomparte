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

/** ZendMax_Application_Resource_ViewAbstract */
require_once 'ZendMax/Application/Resource/LoadscriptAbstract.php';

/**
 * \ZendMax\Application\Resource\Headscript
 *
 * @uses       \ZendMax\Application\Resource\ViewAbstract
 * @package    \ZendMax\Application
 * @subpackage Resource
 */
class Headscript
    extends LoadscriptAbstract
{
    /**
     * Viewhelper to use to render the scripts
     * @var string viewHelper
     */
    protected $_viewHelper = 'headScript';
}