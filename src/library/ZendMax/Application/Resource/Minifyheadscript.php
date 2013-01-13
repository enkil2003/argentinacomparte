<?php
namespace ZendMax\Application\Resource;

/** ZendMax_Application_Resource_ViewAbstract */
require_once 'ZendMax/Application/Resource/LoadscriptAbstract.php';

/**
 * \ZendMax\Application\Resource\Footerscript
 *
 * @uses       \ZendMax\Application\Resource\ViewAbstract
 * @package    \ZendMax\Application
 * @subpackage Resource
 */
class MinifyHeadScript
    extends LoadscriptAbstract
{
    /**
     * Viewhelper to use to render the scripts
     * @var string viewHelper
     */
    protected $_viewHelper = 'minifyHeadScript';
}