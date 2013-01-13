<?php

namespace ZendMax\Db\Doctrine\Mapper;

require_once(__DIR__ . '/../Exception.php');

class Exception extends \ZendMax\Db\Doctrine\Exception
{

    public static $dataArrayNoKeyField = array(
        'code'        => 301,
        'message'  => 'Invalid data: key "fields" was expected'
    );
    public static $whereWithErrors = array(
        'code'        => 301,
        'message'  => 'Where clause has errors'
    );

    
    
}
