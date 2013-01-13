<?php
namespace ZendMax\Validate;
class Dependency extends \Zend_Validate_Abstract
{
    /**
     * Controlls when dependant validators should be added.
     * If true, validators wll be added if the token value is left empty.
     * If false, validators will be added if the token value is provided.
     * @var boolean
     */
    protected $_addValidatorsIfTokenValueProvided = true;
    
    /**
     * Error codes
     * @const string
     */
    const MISSING_TOKEN = 'missingToken';
    
    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::MISSING_TOKEN => 'No token was provided to match against'
    );

    /**
     * @var array
     */
    protected $_messagesVariables = array();

    /**
     * Sets validator options
     *
     * @param  mixed $token
     * @return void
     */
    public function __construct($token)
    {
        if ($token instanceof Zend_Config) {
            $token = $token->toArray();
        }
        if (is_array($token) && array_key_exists('token', $token)) {
            if (array_key_exists('strict', $token)) {
                $this->setStrict($token['strict']);
            }
            $this->setToken($token['token']);
            $this->setDependantRules($token['dependantRules']);
            if (isset($token['addValidatorsIfTokenValueProvided'])) {
                $this->setAddValidatorsIfTokenValueProvided($token['addValidatorsIfTokenValueProvided']);
            }
        }
    }
    
    public function setAddValidatorsIfTokenValueProvided($validate)
    {
        $this->_addValidatorsIfTokenValueProvided = (bool) $validate;
        return $this;
    }
    
    public function setDependantRules($rules)
    {
        $this->_dependantRules = $rules;
        return $this;
    }

    /**
     * Retrieve token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Set token against which to compare
     *
     * @param  mixed $token
     * @return Zend_Validate_Identical
     */
    public function setToken($token)
    {
        $this->_token = $token;
        return $this;
    }

    /**
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, array $context = null)
    {
        $this->_setValue((string) $value);
        $tokenValue = $context[$this->getToken()];
        
        if (($context !== null) && isset($context) && array_key_exists($this->getToken(), $context)) {
            $tokenValue = $context[$this->getToken()];
        } else {
            $tokenValue = $this->getToken();
        }
        
        if ($tokenValue === null) {
            $this->_error(self::MISSING_TOKEN);
            return false;
        }
        
        $valid = true;
        if (
            ($this->_addValidatorsIfTokenValueProvided && $tokenValue) ||
            (!$this->_addValidatorsIfTokenValueProvided && !$tokenValue)
         ) {
            foreach ($this->_dependantRules as $validator) {
                $namespace = isset($validator['namespace'])? $validator['namespace']:'Zend';
                $validatorClass = "\\{$namespace}_Validate_{$validator['validator']}";
                $concreteValidator = new $validatorClass($validator['options']);
                $concreteValidator->setMessages($validator['options']['messages']);
                $valid = $concreteValidator->isValid($value);
                
                if (!$valid) {
                    if (isset($validator['options']['messages'])) {
                        if (is_array($messages = $concreteValidator->getMessages())) {
                            foreach($messages as $messageKey => $messageValue) {
                                $this->_error($messageKey);
                                $this->_messageTemplates[$messageKey] = $messageValue;
                                $this->_messages[$messageKey] = $messageValue;
                            }
                        }
                    }
                }
                if (!$valid && $validator['breakChainOnFailure']) {
                    break;
                }
            }
        }
        return $valid;
    }
}
