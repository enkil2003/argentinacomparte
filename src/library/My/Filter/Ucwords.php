<?php
class My_Filter_Ucwords implements Zend_Filter_Interface
{
    /**
    * string $value Returns the modified string 
    */
    public function filter($value)
    {
        return ucwords($value);
    }
}