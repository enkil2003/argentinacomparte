<?php
class My_Validate_UsernameOrEmail extends Zend_Validate_Abstract
{
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        
        $this->_options = $options;
    }
    
    public function setAdapter($adapter)
    {
        $this->_adapter = $adapter;
    }
    
    function isValid($value)
    {
        if(strpos($value, '@') === false) {
            // username
            $validator = new $this->_options['class']($this->_options['table'], $this->_options['field']);
            return $validator->isValid($value);
        } else {
            // email
            return Zend_Validate::is($value, 'EmailAddress');
        }
    }
}