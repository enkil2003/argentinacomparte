<?php
namespace ZendMax\View\Helper;

/** Zend_View_Helper_HeadLink */
require_once 'Zend/View/Helper/HeadLink.php';

/**
 * ZendMax_View_Helper_HeadLink
 * the "sizes" attributes has been added to supported $_itemKeys
 *
 * @uses       \Zend_View_Helper_HeadLink
 * @package    \ZendMax\View
 * @subpackage Helper
 */
class HeadLink extends \Zend_View_Helper_HeadLink
{
    /**
     * $_validAttributes
     *
     * @var array
     */
    protected $_itemKeys = array(
        'charset',
        'href',
        'hreflang',
        'id',
        'media',
        'rel',
        'rev',
        'type',
        'title',
        'extras',
        'sizes'
    );
}
