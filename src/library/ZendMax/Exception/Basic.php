<?php

namespace Etermax\Exception;

class Basic extends \Exception
{

    /**
     * @var string
     */
    protected $_originalMsg;
    
    /**
     * @var array
     */    
    protected $_extentionsMsg = array();
    
    //--------------------------------------------    
    // System Exceptions: 
    //--------------------------------------------    
    
    //--------------------------------------------
    // User Exceptions: 
    //--------------------------------------------

    public static $dataTypeNoInteger = array(
        'code'        => 1,
        'message'  => 'Invalid data type: integer was expected.'
    );
    public static $dataTypeNoString = array(
        'code'        => 2,
        'message'  => 'Invalid data type: string was expected.'
    );    
    public static $dataTypeNoArray = array(
        'code'        => 3,
        'message'  => 'Invalid data type: array was expected.'
    );
    public static $dataTypeNoFloat = array(
        'code'        => 4,
        'message'  => 'Invalid data type: float was expected.'
    );        
    public static $dataTypeNoObject = array(
        'code'        => 4,
        'message'  => 'Invalid data type: object was expected.'
    );            
    public static $dataTypeNoNull = array(
        'code'        => 4,
        'message'  => 'Invalid data type: null was expected.'
    );                
    public static $dataTypeNoResource = array(
        'code'        => 4,
        'message'  => 'Invalid data type: resource was expected.'
    );  
    public static $dataTypeNoBool = array(
        'code'        => 5,
        'message'  => 'Invalid data type: bool was expected.'
    );  
    public static $dataTypeNoValid = array(
        'code'        => 6,
        'message'  => 'Invalid data type.'
    ); 
    public static $dataNoValid = array(
        'code'        => 7,
        'message'  => 'Invalid data.'
    );
    
    
    /**
     * Differents forms to create an Exception:
     * 
     * 1) throw new \Etermax\Exception\Basic('custom message', 99);
     * 
     * 2) throw new \Etermax\Exception\Basic('@dataTypeNoInteger');
     * 
     * 3) throw new \Etermax\Exception\Basic(array(
     *                           'prop'    => '@dataTypeNoInteger', 
     *                           'extend' => 'verify $id parameter'));
     * 
     * 4) throw new \Etermax\Exception\Basic(array(
     *                          'prop'   => '@dataTypeNoInteger',  
     *                          'rewrite'=> 'verify $id parameter'));
     * 
     * @param string|array $message
     * @param null|int $code_message
     * @param null $previous 
     */
    public function __construct ($message, $code = null, $previous = null) 
    {
        if(is_array($message)){
            $propName = substr($message['prop'], 1);
            $extend  = isset($message['extend']) ? $message['extend']: null; 
            $rewrite  = isset($message['rewrite']) ? $message['rewrite']: null; 
        }
        if(is_string($message) && $message[0] == '@'){
            $propName = substr($message, 1);
        }
        if(isset($propName)){
            $className = get_class($this);
            if(isset($className::${$propName})){
                $message = $className::${$propName}['message'];
                $code = $className::${$propName}['code'];
            }
        }
        
        parent::__construct($message, $code);
        
        $this->_originalMsg = $message;
        isset($extend) ? $this->extendMsg($extend) : null;
        isset($rewrite) ? $this->rewriteMsg($rewrite): null;
    }
    
    /**
     * Help to extend the definition of the message
     * preserving the primitive message, and preserving 
     * the code, and adding a custom description.
     * 
     * @param string $message
     * @return \Etermax\Exception\Basic
     * @throws Basic 
     */
    public function extendMsg($message)
    {
        $this->_extentionsMsg[] = $message;
        if(substr($this->message, -1) != '.'){
           $this->message .= '.';
        }
        $this->message .= ' ' . $message;
        return $this;
    }
    
    /**
     * Let you define your custom message and preserve
     * the code.
     * 
     * @param type $message
     * @return \Etermax\Exception\Basic
     * @throws Basic 
     */    
    public function rewriteMsg($message)
    {
        if(!is_string($message)){
            throw new Basic('@dataTypeNoString');
        }        
        $this->message = $message;
        return $this;
    }    
    
    /**
    * Get the first Msg without any extend message
     * 
    * @return string
    */    
    public function getOriginalMsg()
    {
        return $this->_originalMsg;
    }
    
    /**
    * Restart the message to its first state without any extends.
     * 
    * @return \Etermax\Exception\Basic
    */
    public function restartMsg()
    {
        $this->message = $this->getOriginalMsg();
        $this->_extentionsMsg = array();
        return $this;
    }
    
    /**
     * Say if it is a system exception
     * 
     * @return bool 
     */
    public function isSystemException()
    {
        return ($this->getCode() <= 0 ) ? true : false;
    }

    /**
     * Say if it is a user exception
     * 
     * @return bool 
     */    
    public function isUserException()
    {
        return ($this->getCode() > 0 ) ? true : false;
    }
}
