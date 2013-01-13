<?php
namespace ZendMax\View\Helper;
/**
 * This helper facilitates twitter interaction and html generation
 * 
 * This is an example about how it can be used with a few of the supported data attributes
 * 
 * $this->view->twitterButtons()
 *            ->setUrl('http://www.google.com')
 *            ->setTwitterAccount('myaccount')
 *            ->setSize(\ZendMax\View\Helper\TwitterButtons::TWITTER_BUTTON_SIZE_MEDIUM)
 *            ->setText('Lets follow this account!')
 *            ->setCount(\ZendMax\View\Helper\TwitterButtons::TWITTER_COUNT_POSITION_NONE)
 *            ->setButtonType(\ZendMax\View\Helper\TwitterButtons::TWITTER_SHARE_BUTTON);
 * 
 * @author Ricardo
 */
class TwitterButtons extends \Zend_View_Helper_Abstract
{
    const TWITTER_SHARE_BUTTON = 'twitter-share-button';
    const TWITTER_FOLLOW_BUTTON = 'twitter-follow-button';
    const TWITTER_HASHTAG_BUTTON = 'twitter-hashtag-button';
    const TWITTER_MENTION_BUTTON = 'twitter-mention-button';
    
    const TWITTER_COUNT_POSITION_NONE = 'none';
    const TWITTER_COUNT_POSITION_HORIZONTAL = 'horizontal';
    const TWITTER_COUNT_POSITION_VERTICAL = 'vertical';
    
    const TWITTER_BUTTON_SIZE_MEDIUM = 'medium';
    const TWITTER_BUTTON_SIZE_LARGE = 'large';
    
    private $_twitterAccount = null;
    private $_extraClasses = null;
    private $_buttonType = 'twitter-share-button';
    
    private $_dataAttribs = array(
        'lang' => 'en',
        'via' => 'twitterapi'
    );
    
    private $_allowedButtonSizes = array(
        'medium',
        'large'
    );
    
    private $_allowedDataAttributes = array(
        'Url',
        'Via',
        'Text',
        'Count',
        'Lang',
        'CountUrl',
        'HashTags',
        'Size',
        'ShowCount',
        'Related'
    );
    
    private $_allowedButtonTypes = array(
        'twitter-share-button',
        'twitter-follow-button',
        'twitter-hashtag-button',
        'twitter-mention-button'
    );
    
    private $_allowedCountPositions = array(
        'none',
        'horizontal',
        'vertical'
    );
    
    /**
     * Returns the view helper
     */
    public function twitterButtons()
    {
        return $this;
    }
    
    /**
     * Convinience method for setting data attributes to the twitter <a /> tag
     * @param string $key
     * @param string $value
     * @throws \Zend_Exception
     * @return \ZendMax\View\Helper\TwitterButtons
     */
    public function __call($method, $params)
    {
        if (substr($method, 0, 3) != 'set') {
            throw new \Zend_Exception("Method $method is not an allowed method");
        }
        $dataProperty = substr($method, 3);
        if (!in_array($dataProperty, $this->_allowedDataAttributes)) {
            throw new \Zend_Exception("Method $dataProperty is not an allowed property");
        }
        $this->_dataAttribs[strtolower($dataProperty)] = $params[0];
        return $this;
    }
    
    /**
     * Retrieves the data attributes ready to be injected in the <a /> tag
     * @return string
     */
    private function _retrieveFormattedDataAttributes()
    {
        $dataAttribs = '';
        $filter = new \Zend_Filter_Word_CamelCaseToDash();
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
     * Convinience method for adding extra classes for customization of the twitter button
     * @param string/array $classes
     * @return \ZendMax\View\Helper\TwitterButtons
     */
    public function addExtraClasses($classes)
    {
        if (is_array($classes)) {
            $classes = implode(' ', $classes);
        }
        $this->_extraClasses = $classes;
        return $this;
    }
    
    /**
     * Sets the position for the twitter's tweet counter
     * @param string $position use constants for convinience
     * @throws Zend_Exception
     * @return \ZendMax\View\Helper\TwitterButtons
     */
    public function setCount($position) {
        if (!in_array($position, $this->_allowedCountPositions)) {
            throw new \Zend_Exception("Position $position is not an allowed count position, use constants for convinience");
        }
        $this->_dataAttribs['count'] = $position;
        return $this;
    }
    
    /**
     * Sets the twitter account.
     * @param string $account
     * @return \ZendMax\View\Helper\TwitterButtons
     */
    public function setTwitterAccount($account)
    {
        $this->_twitterAccount = $account;
        return $this;
    }
    
    public function setSize($size)
    {
        if (!in_array($size, $this->_allowedButtonSizes)) {
            throw new \Zend_Exception("Size $size is not an allowed size, use constants for convinience");
        }
        $this->_dataAttribs['size'] = $size;
        return $this;
    }
    
    /**
     * Sets the twitter button behaviour class type.
     * @param string $type
     * @throws Zend_Exception
     * @return \ZendMax\View\Helper\TwitterButtons
     */
    public function setButtonType($type)
    {
        if (!in_array($type, $this->_allowedButtonTypes)) {
            throw new \Zend_Exception("Button $type is not an allowed button type, use constants for convinience");
        }
        $this->_buttonType = $type;
        return $this;
    }
    
    /**
     * Returns the formated href based on the twitter behaviour. ie: share, follow, hastag and mention.
     * @return string
     */
    private function _getHref()
    {
        $href = 'https://twitter.com/';
        switch ($this->_buttonType) {
            case self::TWITTER_FOLLOW_BUTTON:
                $href .= $this->_twitterAccount;
                break;
            case self::TWITTER_HASHTAG_BUTTON:
                $href .= 'intent/tweet?button_hashtag=TwitterStories';
                break;
            case self::TWITTER_MENTION_BUTTON:
                $href .= "intent/tweet?screen_name={$this->_twitterAccount}";
                break;
            case self::TWITTER_SHARE_BUTTON:
            default:
                $href .= 'share';
                break;
        }
        return $href;
    }
    
    /**
     * Generates the twitter button's html
     * @return string
     */
    public function render()
    {
        $dataAttribs = $this->_retrieveFormattedDataAttributes();
        $href = $this->_getHref();
        $followButton = <<<HTML
<a href="{$href}" class="{$this->_buttonType} {$this->_extraClasses}" {$dataAttribs}>@{$this->_twitterAccount}</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
HTML;
        return $followButton;
    }
    
    /**
     * Easy way to print the button
     * @return String Html for the desired twitter button
     */
    public function __toString()
    {
        return $this->render();
    }
}
