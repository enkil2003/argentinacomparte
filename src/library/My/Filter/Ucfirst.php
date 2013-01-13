<?php
require_once 'Zend/Filter/Interface.php';
class My_Filter_Ucfirst implements Zend_Filter_Interface
{
    /**
    * string $value Returns the modified string 
    */
    public function filter($value)
    {
        return ucfirst($value);
    }
}