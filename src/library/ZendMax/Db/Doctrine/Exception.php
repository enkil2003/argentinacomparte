<?php

namespace ZendMax\Db\Doctrine;

require_once(__DIR__ . '/../../Exception/Basic.php');

class Exception extends \Etermax\Exception\Basic
{
    //-------------------------------------------- 
    // System Exceptions:
    //--------------------------------------------    
    
    public static $internalError = array(
        'code'        => -200,
        'message'  => 'internal error'
    );
    public static $timeOut = array(
        'code'        => -201,
        'message'  => 'time out'
    );    
    
    //--------------------------------------------
    // User Exceptions:
    //--------------------------------------------
    
    public static $resourceNotFound = array(
        'code'        => 200,
        'message'  => 'resource not found'
    );
    public static $incompleteData = array(
        'code'        => 201,
        'message'  => 'incomplete data'
    );
    public static $assocPropNotAllowed = array(
        'code'        => 202,
        'message'  => 'Association property is not allowed.'
    );
    public static $propertyNotExist = array(
        'code'        => 203,
        'message'  => 'Property not exist'
    );          
    public static $idRequired = array(
        'code'        => 204,
        'message'  => 'Id is required.'
    );   
    public static $resourcesNotRelation = array(
        'code'        => 205,
        'message'  => 'These resources haven\'t a relation'
    );    
    
}
