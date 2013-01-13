<?php
namespace ZendMax\View\Helper;
/** Zend_View_Helper_HeadScript */
require_once 'Zend/View/Helper/HeadScript.php';

/**
 * Helper for setting and retrieving script elements for HTML head section
 *
 * @uses       \Zend_View_Helper_HeadScript
 * @package    \ZendMax\View
 * @subpackage Helper
 */
abstract class ScriptLoaderAbstract extends \Zend_View_Helper_HeadScript
{
    /**
     * Create script HTML
     *
     * @param  string $type
     * @param  array $attributes
     * @param  string $content
     * @param  string|int $indent
     * @return string
     */
    public function itemToString($item, $indent, $escapeStart, $escapeEnd)
    {
        if (isset($item->attributes['source'])) {
            $item->source = $item->attributes['source'];
        }
        return parent::itemToString($item, $indent, $escapeStart, $escapeEnd);
    }
}
