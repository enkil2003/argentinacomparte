<?php
/**
 * This helper facilitates Google Plus interaction and html generation
 * 
 * @author Ricardo
 */
class My_View_Helper_GooglePlusButtons extends Zend_View_Helper_Abstract
{
    const GOOGLE_PLUS_SIZE_SMALL = 'small';
    const GOOGLE_PLUS_SIZE_MEDIUM = 'medium';
    const GOOGLE_PLUS_SIZE_STANDARD = 'standard';
    const GOOGLE_PLUS_SIZE_TALL = 'tall';
    
    const GOOGLE_PLUS_ANNOTATION_NONE = 'none';
    const GOOGLE_PLUS_ANNOTATION_BUBBLE = 'bubble';
    const GOOGLE_PLUS_ANNOTATION_INLINE = 'inline';
    
    const GOOGLE_PLUS_ALIGN_RIGHT = 'right';
    const GOOGLE_PLUS_ALIGN_LEFT = 'left';
    
    private $_dataAttribs = array(
        'size' => 'standard', // Zend_View_Helper_GooglePlusButtons::GOOGLE_PLUS_SIZE_STANDARD
        'annotation' => 'bubble', // Zend_View_Helper_GooglePlusButtons::GOOGLE_PLUS_ANNOTATION_BUBBLE
        'align' => 'left',
        'expandTo' => ''
    );
    
    private $_allowedSizes = array(
        'small',
        'medium',
        'standard',
        'tall'
    );
    
    const GOOGLE_PLUS_EXPAND_TOP = 'top';
    const GOOGLE_PLUS_EXPAND_RIGHT = 'right';
    const GOOGLE_PLUS_EXPAND_BOTTOM = 'bottom';
    const GOOGLE_PLUS_EXPAND_LEFT = 'left';
    
    private $_allowedExpendTo = array(
        'top',
        'right',
        'bottom',
        'left'
    );
    
    // https://developers.google.com/+/plugins/+1button/#plusonetag-parameters
    private $_allowedDataAttributes = array(
        'Href',
        'Size',
        'Annotation',
        'Width',
        'Align',
        'ExpandTo',
        'Callback',
        'Onstartinteraction',
        'Onendinteraction'
    );
    
    private $_allowedAligns = array(
        'left', // Zend_View_Helper_GooglePlusButtons::GOOGLE_PLUS_ALIGN_LEFT
        'right'// Zend_View_Helper_GooglePlusButtons::GOOGLE_PLUS_ALIGN_RIGHT
    );
    
    private $_allowedAnnotations = array(
        'none', // Zend_View_Helper_GooglePlusButtons::GOOGLE_PLUS_ANNOTATION_NONE
        'bubble', // Zend_View_Helper_GooglePlusButtons::GOOGLE_PLUS_ANNOTATION_BUBBLE
        'inline' //// Zend_View_Helper_GooglePlusButtons::GOOGLE_PLUS_ANNOTATION_INLINE
    );
    
    private $_scriptAttribs = null;
    
    // https://developers.google.com/+/plugins/+1button/#script-parameters
    private $_allowedScriptAttribs = array(
        'lang',
        'parsetags'
    );
    
    /**
     * Sets the size for the button
     * @param string $size
     * @throws Zend_Exception
     * @return Zend_View_Helper_GooglePlusButtons
     */
    public function setSize($size)
    {
        if (!in_array($size, $this->_allowedSizes)) {
            throw new Zend_Exception("Size $size is not a valid size, use convinience constants");
        }
        $this->_dataAttribs['size'] = $size;
        return $this;
    }
    
    /**
     * Sets the alignment for the button
     * @param string $align
     * @throws Zend_Exception
     * @return Zend_View_Helper_GooglePlusButtons
     */
    public function setAlign($align)
    {
        if (!in_array($align, $this->_allowedAligns)) {
            throw new Zend_Exception("Alignment $align is not a valid align, use convinience constants");
        }
        $this->_dataAttribs['align'] = $align;
        return $this;
    }
    
    /**
     * Sets the expand to directives
     * @param string/string comma separated/array $expandTo
     * @throws Zend_Exception
     * @return Zend_View_Helper_GooglePlusButtons
     */
    public function setExpandTo($expandTo)
    {
        if (is_string($expandTo) && strpos($expandTo, ',')) {
            $expandTo = explode(',', $expandTo);
        }
        $allowedExpandTo = $this->_allowedExpendTo;
        if (is_array($expandTo)) {
            array_walk(
                $expandTo,
                function($value, $key) use($allowedExpandTo)
                {
                    if (!in_array($value, $allowedExpandTo)) {
                        throw new Zend_Exception("Expand to $value is not a valid expand to value, use convinience constants");
                    }
                }
            );
            $expandTo = implode(',', $expandTo);
        } else if (!in_array($expandTo, $this->_allowedExpendTo)) {
            throw new Zend_Exception("Expand to $val is not a valid expand to value, use convinience constants");
        }
        
        $this->_dataAttribs['expandTo'] = $expandTo;
        return $this;
    }
    
    /**
     * Sets the annotation for the button
     * @param string $annotation
     * @throws Zend_Exception
     * @return Zend_View_Helper_GooglePlusButtons
     */
    public function setAnnotation($annotation)
    {
        if (!in_array($annotation, $this->_allowedAnnotations)) {
            throw new Zend_Exception("Annotation $annotation is not a valid annotation, use convinience constants");
        }
        $this->_dataAttribs['annotation'] = $annotation;
        return $this;
    }
    
    /**
     * Returns the view helper
     */
    public function googlePlusButtons()
    {
        return $this;
    }
    
    /**
     * Convinience method for setting data attributes
     * @param string $key
     * @param string $value
     * @throws Zend_Exception
     * @return Zend_View_Helper_TwitterButtons
     */
    public function __call($method, $params)
    {
        if (substr($method, 0, 3) != 'set') {
            throw new Zend_Exception("Method $method is not an allowed method");
        }
        $dataProperty = substr($method, 3);
        if (!in_array($dataProperty, $this->_allowedDataAttributes)) {
            throw new Zend_Exception("Method $dataProperty is not an allowed property");
        }
        $this->_dataAttribs[$dataProperty] = $params[0];
        return $this;
    }
    
    /**
     * Retrieves the data attributes ready to be injected in the <a /> tag
     * @return string
     */
    private function _retrieveFormattedDataAttributes()
    {
        $dataAttribs = '';
        $filter = new Zend_Filter_Word_CamelCaseToDash();
        foreach ($this->_dataAttribs as $key => $value) {
            $key = strtolower($filter->filter($key));
            if ($value === true) {
                $value = 'true';
            } else if($value === false) {
                $value = 'false';
            }
            $dataAttribs .= "data-$key=\"$value\" ";
        }
        return $dataAttribs;
    }
    
    /**
     * Generates the button's html
     * @return string
     */
    public function render()
    {
        $dataAttribs = $this->_retrieveFormattedDataAttributes();
        $googlePlusButton = <<<HTML
<div class="g-plusone" {$dataAttribs}></div>
<script type="text/javascript" {$this->_scriptAttribs}>
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
HTML;
        return $googlePlusButton;
    }
    
    /**
     * Easy way to print the button
     * @return String Html for the desired button
     */
    public function __toString()
    {
        return $this->render();
    }
}
