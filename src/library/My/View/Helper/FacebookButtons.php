<?php
/**
 * This helper facilitates facebook interaction and html generation
 * @author Ricardo
 */
class My_View_Helper_FacebookButtons extends Zend_View_Helper_Abstract
{
    private $_allowedDataAttributes = array(
        'AppId',
        'Href',
        'Send',
        'Layout',
        'Width',
        'ShowFaces',
        'Font',
        'Size',
        'ShowCount',
        'Related'
    );
    
    private $_dataAttribs = array();
    
    /**
     * Convinience method for setting data attributes to the twitter <a /> tag
     * @param string $key
     * @param string $value
     * @throws Zend_Exception
     * @return Zend_View_Helper_TwitterButtons
     */
    public function __call($method, $params)
    {
        $action = substr($method, 0, 3);
        if ($action != 'set' && $action != 'get') {
            throw new Zend_Exception("Method $method is not an allowed method");
        }
        $dataProperty = substr($method, 3);
        if (!in_array($dataProperty, $this->_allowedDataAttributes)) {
            throw new Zend_Exception("Method $dataProperty is not an allowed property");
        }
        
        switch($action) {
            case 'set':
                $this->_dataAttribs[$dataProperty] = $params[0];
                $return = $this;
                break;
            case 'get':
                $return = $this->_dataAttribs[$dataProperty];
                break;
        }
        return $return;
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
    
    public function facebookButtons()
    {
        return $this;
    }
    
    public function javascriptSDK()
    {
        $sdk = <<<HTML
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId={$this->getAppId()}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
HTML;
        return $sdk;
    }
    
    public function toString()
    {
        $dataAttribs = $this->_retrieveFormattedDataAttributes();
        $likeButton = <<<HTML
<div class="fb-like" {$dataAttribs}></div>
HTML;
        return $likeButton;
    }
    
    /**
     * Easy way to print the button
     * @return String Html for follow me button
     * @TODO throw exeptions if there are missing properties
     */
    public function __toString()
    {
        return $this->toString();
    }
}