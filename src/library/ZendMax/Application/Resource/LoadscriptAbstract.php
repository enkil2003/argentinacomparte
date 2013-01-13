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
require_once 'ZendMax/Application/Resource/ViewAbstract.php';

/**
 * \ZendMax\Application\Resource\LoadscriptAbstract
 *
 * @uses       \ZendMax\Application\Resource\ViewAbstract
 * @package    \ZendMax\Application
 * @subpackage Resource
 */
abstract class LoadscriptAbstract
    extends ViewAbstract
{
    protected $_viewHelper = null;
    // @codingStandardsIgnoreStart
    protected $_fallbackTpl = <<<SCRIPT
%condition% || document.write(unescape('%3Cscript src="%file%"%3E%3C/script%3E'))
SCRIPT;
    // @codingStandardsIgnoreEnd

    /**
     * Initialises the resource
     * @see \Zend_Application_Resource_Resource::init()
     * @return LoadscriptAbstract
     */
    public function init()
    {
        $this->_loadScripts();
        return $this;
    }
    
    /**
     * Loads scripts from application.ini
     * @return void
     */
    protected function _loadScripts()
    {
        $options = $this->getOptions();
        if (!isset($options) && !count($options)) {
            return;
        }
        
        foreach ($options as $file) {
            $type = isset($file['type'])? $file['type']:'text/javascript';
            $attrs = isset($file['attrs'])? $file['attrs']: array();
            $this->_view->{$this->_viewHelper}()->appendFile(
                $file['file'],
                isset($file['type'])? $file['type']:'text/javascript',
                isset($file['attrs'])? $file['attrs']:array()
            );
            
            if (isset($file['fallback'])) {
                if (
                    !$file['fallback']['condition']
                    || !$file['fallback']['file']
                ) {
                    require_once 'Zend\Application\Resource\Exception.php';
                    throw new \Zend_Application_Resource_Exception(
                        "You required to provide a condition and a fallback"
                        . "file"
                    );
                }
                // cannot use sprintf due to the escaped javascript
                $tpl = str_replace(
                    '%condition%',
                    $file['fallback']['condition'],
                    $this->_fallbackTpl
                );
                $tpl = str_replace('%file%', $file['fallback']['file'], $tpl);
                $tpl .= PHP_EOL;
                $this->_view->{$this->_viewHelper}()->appendScript($tpl);
            }
        }
    }
}