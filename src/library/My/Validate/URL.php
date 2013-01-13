<?php
class My_Validate_URL extends Zend_Validate_Abstract {
    /* (non-PHPdoc)
     * @see Zend_Validate_Interface::isValid()
     */
    public function isValid($value) {
        return $this->_URLExist($value);
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $url
     */
    function _URLExist($url) {
        $c=curl_init();
        curl_setopt($c,CURLOPT_URL,$url);
        curl_setopt($c,CURLOPT_HEADER,1);
        curl_setopt($c,CURLOPT_NOBODY,1);
        curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($c,CURLOPT_FRESH_CONNECT,1);
        if(!curl_exec($c)){
            return false;
        } else {
            return true;
        }
    }
}