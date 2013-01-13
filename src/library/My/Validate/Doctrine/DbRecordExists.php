<?php
require_once 'Db/Abstract.php';
class My_Validate_Doctrine_DbRecordExists extends My_Validate_Doctrine_Db_Abstract
{
    /**
     * Will return true if the value is listed in the table's field
     * @see Zend_Validate::isValid()
     */
    public function isValid($value)
    {
        $valid = true;
        if (!(bool)$this->getCount($value)) {
            $valid = false;
            $this->_error(self::ERROR_NO_RECORD_FOUND);
        }
        return $valid;
    }
}
