<?php
namespace ZendMax\View\Helper;

/** Zend_View_Helper_Placeholder_Container_Standalone */
require_once 'Zend/View/Helper/Placeholder/Container/Standalone.php';

/**
 * ZendMax_Layout_View_Helper_OpenGraph
 *
 * @uses       \Zend_View_Helper_Placeholder_Container_Standalone
 * @package    \ZendMax\View
 * @subpackage Helper
 */
class OpenGraph
    extends \Zend_View_Helper_Placeholder_Container_Standalone
{
    /**
     * Placement for the open graph
     * @var unknown_type
     */
    const APPEND = 'append';
    
    /**
     * Placement for the open graph
     * @var unknown_type
     */
    const PREPEND = 'prepend';
    
    /**
     * Placement for the open graph
     * @var unknown_type
     */
    const SET = 'set';
    /**
     * @var string registry key
     */
    protected $_regKey = 'ZendMax_View_Helper_OpenGraph';
    
    /**
     * Constructor
     *
     * Set separator to PHP_EOL
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setSeparator(PHP_EOL);
    }
    
    /**
     * Retrieve object instance; optionally add meta tag
     *
     * @param  string $content
     * @param  string $keyValue
     * @param  array $modifiers
     * @param  string $placement
     * @return OpenGraph
     */
    public function openGraph
    (
        $content = null,
        $type = null,
        $modifiers = array(),
        $placement = OpenGraph::APPEND
    )
    {
        if ((null !== $content) && (null !== $type)) {
            $item  = $this->createData($type, $content, $modifiers);
            $action = strtolower($placement);
            switch ($action) {
                case 'append':
                case 'prepend':
                case 'set':
                    $this->$action($item);
                    break;
                default:
                    $this->append($item);
                    break;
            }
        }
    
        return $this;
    }
    
    /**
     * Normalizes the property type
     * @param string $type
     * @return string
     */
    protected function _normalizeType($type)
    {
        return strtolower($type);
    }
    
    /**
     * Create data item for inserting into stack
     *
     * @param  string $type
     * @param  string $typeValue
     * @param  string $content
     * @param  array $modifiers
     * @return stdClass
     */
    public function createData($type, $typeValue, $content, array $modifiers)
    {
        $data            = new \stdClass;
        $data->type      = $type;
        $data->typeValue = $typeValue;
        $data->content   = $content;
        $data->modifiers = $modifiers;
        return $data;
    }
    
    /**
     * Overload method access
     *
     * Allows the following 'virtual' methods:
     * - appendProperty($keyValue, $content, $modifiers = array())
     * - offsetGetProperty($index, $keyValue, $content, $modifiers = array())
     * - prependProperty($keyValue, $content, $modifiers = array())
     * - setProperty($keyValue, $content, $modifiers = array())
     *
     * @param  string $method
     * @param  array $args
     * @return Zend_View_Helper_HeadMeta
     */
    public function __call($method, $args)
    {
        if (
            preg_match(
                '/^(?P<action>set|(pre|ap)pend|offsetSet)(?P<type>Property)$/',
                $method,
                $matches
            )
        ) {
            $action = $matches['action'];
            $type   = $this->_normalizeType($matches['type']);
            $argc   = count($args);
            $index  = null;
            
            if ('offsetSet' == $action) {
                if (0 < $argc) {
                    $index = array_shift($args);
                    --$argc;
                }
            }
            
            if (2 > $argc) {
                require_once 'Zend/View/Exception.php';
                $e = new \Zend_View_Exception(
                    'Too few arguments provided; '
                    . ' requires key value, and content'
                );
                $e->setView($this->view);
                throw $e;
            }
            
            if (3 > $argc) {
                $args[] = array();
            }
            
            $item  = $this->createData($type, $args[0], $args[1], $args[2]);
            
            if ('offsetSet' == $action) {
                return $this->offsetSet($index, $item);
            }
            
            $this->$action($item);
            return $this;
        }
        
        return parent::__call($method, $args);
    }
    
    /**
     * Determine if item is valid
     *
     * @param  mixed $item
     * @return boolean
     */
    protected function _isValid($item)
    {
        if (
            (!$item instanceof \stdClass)
            || !isset($item->type)
            || !isset($item->modifiers)
        ) {
            return false;
        }
        
        if (
            !isset($item->content) &&
            (
                ! $this->view->doctype()->isHtml5()
                || (
                    !$this->view->doctype()->isHtml5()
                    && $item->type !== 'charset'
                )
            )
        ) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Append
     *
     * @param  string $value
     * @return void
     * @throws Zend_View_Exception
     */
    public function append($value)
    {
        if (!$this->_isValid($value)) {
            require_once 'Zend/View/Exception.php';
            $e = new \Zend_View_Exception(
                'Invalid value passed to append; please use appendMeta()'
            );
            $e->setView($this->view);
            throw $e;
        }
        
        return $this->getContainer()->append($value);
    }
    
    /**
     * OffsetSet
     *
     * @param  string|int $index
     * @param  string $value
     * @return void
     * @throws Zend_View_Exception
     */
    public function offsetSet($index, $value)
    {
        if (!$this->_isValid($value)) {
            require_once 'Zend/View/Exception.php';
            $e =  new \Zend_View_Exception(
                'Invalid value passed to offsetSet; '
                . 'please use offsetSetName() or offsetSetHttpEquiv()'
            );
            $e->setView($this->view);
            throw $e;
        }
    
        return $this->getContainer()->offsetSet($index, $value);
    }
    
    /**
     * OffsetUnset
     *
     * @param  string|int $index
     * @return void
     * @throws Zend_View_Exception
     */
    public function offsetUnset($index)
    {
        if (!in_array($index, $this->getContainer()->getKeys())) {
            require_once 'Zend/View/Exception.php';
            $e = new \Zend_View_Exception(
                'Invalid index passed to offsetUnset()'
            );
            $e->setView($this->view);
            throw $e;
        }
        
        return $this->getContainer()->offsetUnset($index);
    }
    
    /**
     * Prepend
     *
     * @param  string $value
     * @return void
     * @throws Zend_View_Exception
     */
    public function prepend($value)
    {
        if (!$this->_isValid($value)) {
            require_once 'Zend/View/Exception.php';
            $e = new \Zend_View_Exception(
                'Invalid value passed to prepend; please use prependMeta()'
            );
            $e->setView($this->view);
            throw $e;
        }
        
        return $this->getContainer()->prepend($value);
    }
    
    /**
     * Set
     *
     * @param  string $value
     * @return void
     * @throws Zend_View_Exception
     */
    public function set($value)
    {
        if (!$this->_isValid($value)) {
            require_once 'Zend/View/Exception.php';
            $e = new \Zend_View_Exception(
                'Invalid value passed to set; please use setMeta()'
            );
            $e->setView($this->view);
            throw $e;
        }
        
        $container = $this->getContainer();
        foreach ($container->getArrayCopy() as $index => $item) {
            if (
                $item->type == $value->type
                && $item->{$item->type} == $value->{$value->type}
            ) {
                $this->offsetUnset($index);
            }
        }
        
        return $this->append($value);
    }
    
    /**
     * Build meta HTML string
     *
     * @param  string $type
     * @param  string $typeValue
     * @param  string $content
     * @param  array $modifiers
     * @return string
     */
    public function itemToString(\stdClass $item)
    {
        $modifiersString = '';
        foreach ($item->modifiers as $key => $value) {
            $modifiersString .= $key . '="' . $this->_escape($value) . '" ';
        }
        
        if ($this->view instanceof Zend_View_Abstract) {
            if ($this->view->doctype()->isHtml5()) {
                $tpl = ($this->view->doctype()->isXhtml())
                    ? '<meta property="%s" content="%s"/>'
                    : '<meta property="%s" content="%s">';
            } elseif ($this->view->doctype()->isXhtml()) {
                $tpl = '<meta property="%s" content="%s" %s/>';
            } else {
                $tpl = '<meta property="%s" content="%s" %s>';
            }
        } else {
            $tpl = '<meta property="%s" content="%s" %s/>';
        }
        
        $meta = sprintf(
            $tpl,
            $this->_escape($item->typeValue),
            $this->_escape($item->content),
            $modifiersString
        );
        return $meta;
    }
    
    /**
     * Render placeholder as string
     *
     * @param  string|int $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $indent = (null !== $indent)
                ? $this->getWhitespace($indent)
                : $this->getIndent();
        
        $items = array();
        $this->getContainer()->ksort();
        try {
            foreach ($this as $item) {
                $items[] = $this->itemToString($item);
            }
        } catch (\Zend_View_Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            return '';
        }
        
        $separator = $this->_escape($this->getSeparator());
        return $indent . implode($separator . $indent, $items);
    }
}
