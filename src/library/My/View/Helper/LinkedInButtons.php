<?php
/**
 * This helper facilitates twitter interaction and html generation
 * 
 * This is an example about how it can be used with a few of the supported data attributes
 * 
 * $this->view->twitterButtons()
 *            ->setUrl('http://www.google.com')
 *            ->setTwitterAccount('myaccount')
 *            ->setSize(Zend_View_Helper_TwitterButtons::TWITTER_BUTTON_SIZE_MEDIUM)
 *            ->setText('Lets follow this account!')
 *            ->setCount(Zend_View_Helper_TwitterButtons::TWITTER_COUNT_POSITION_NONE)
 *            ->setButtonType(Zend_View_Helper_TwitterButtons::TWITTER_SHARE_BUTTON);
 * 
 * @author Ricardo
 */
class My_View_Helper_LinkedInButtons extends Zend_View_Helper_Abstract
{
    const LINKEDIN_COUNTER_POSITION_VERTICAL = 'top';
    const LINKEDIN_COUNTER_POSITION_HORIZONTAL = 'right';
    const LINKEDIN_COUNTER_POSITION_NONE = 'none';
    
    private $_dataAttribs = array();
    
    private $_allowedCounterPositions = array(
        'top',
        'right',
        'none'
    );
    
    private $_allowedDataAttributes = array(
        'Url',
        'Counter'
    );
    
    
    /**
     * Returns the view helper
     */
    public function linkedInButtons()
    {
        return $this;
    }
    
    /**
     * Convinience method for setting data attributes to the linked in <script /> tag
     * @param string $key
     * @param string $value
     * @throws Zend_Exception
     * @return Zend_View_Helper_LinkedInButtons
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
     * Retrieves the data attributes ready to be injected in the <script /> tag
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
     * Sets the position for the linkedin's counter
     * @param string $position use constants for convinience
     * @throws Zend_Exception
     * @return Zend_View_Helper_LinkedInButtons
     */
    public function setCounter($position) {
        if (!in_array($position, $this->_allowedCounterPositions)) {
            throw new Zend_Exception("Position $position is not an allowed count position, use constants for convinience");
        }
        $this->_dataAttribs['counter'] = $position;
        return $this;
    }
    
    /**
     * Generates the linked in button's html
     * @return string
     */
    public function render()
    {
        $dataAttribs = $this->_retrieveFormattedDataAttributes();
        $linkedInButton = <<<HTML
<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
<script type="IN/Share" {$dataAttribs}></script>
HTML;
        return $linkedInButton;
    }
    
    /**
     * Easy way to print the button
     * @return String Html for linked in button
     */
    public function __toString()
    {
        return $this->render();
    }
}
