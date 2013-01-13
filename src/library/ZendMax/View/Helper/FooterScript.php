<?php
namespace ZendMax\View\Helper;

/** Zend_View_Helper_HeadScript */
require_once 'Zend/View/Helper/HeadScript.php';

/**
 * Helper for setting and retrieving script elements for HTML at bottom of
 * the body section
 *
 * @uses       \Zend_View_Helper_HeadScript
 * @package    \ZendMax\View
 * @subpackage Helper
 */
class FooterScript extends ScriptLoaderAbstract
{
    /**
     * Registry key for placeholder
     * @var string
     */
    protected $_regKey = 'ZendMax_View_Helper_FooterScript';

    /**
     * Returns footerScript helper object; optionally, allows specifying a
     * script or script file to include.
     *
     * @param  string $mode Script or file
     * @param  string $spec Script/url
     * @param  string $placement Append, prepend, or set
     * @param  array $attrs Array of script attributes
     * @param  string $type Script type and/or array of script attributes
     * @return FooterScript
     */
    public function footerScript(
        $mode = \Zend_View_Helper_HeadScript::FILE,
        $spec = null,
        $placement = 'APPEND',
        array $attrs = array(),
        $type = 'text/javascript'
    )
    {
        parent::headScript($mode, $spec, $placement, $attrs, $type);
        return $this;
    }
    
    /**
     * Do not allow to call inherit headScript method
     * @throws \Zend_View_Exception
     * @return void
     */
    public function headScript($mode = \Zend_View_Helper_HeadScript::FILE, $spec = null, $placement = 'APPEND', array $attrs = array(), $type = 'text/javascript')
    {
        require_once 'Zend/View/Exception.php';
        throw new \Zend_View_Exception(
            "You cannot call headScript from footerScript"
        );
    }
}
