<?php

namespace Etermax\ApiRest;

require_once(__DIR__ . '/../Exception/Basic.php');

class Exception extends \Etermax\Exception\Basic
{
    //-------------------------------------------- 
    // System Exceptions:
    //--------------------------------------------    
    
    public static $internalError = array(
        'code'        => -200,
        'message'  => 'internal error'
    );
    public static $decodingJsonFailed = array(
        'code'        => -201,
        'message'  => 'Json decoding  failed. Verify the sintax'
    );    
    
    //--------------------------------------------
    // User Exceptions:
    //--------------------------------------------
    
    public static $resourceNotFound = array(
        'code'        => 200,
        'message'  => 'resource not found'
    );

    
}