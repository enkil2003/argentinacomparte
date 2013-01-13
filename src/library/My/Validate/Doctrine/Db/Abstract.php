<?php
abstract class My_Validate_Doctrine_Db_Abstract extends Zend_Validate
{
    /**
     * Error constants
     */
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';
    /**
     * @var array Message templates
     */
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record matching %s was found",
        self::ERROR_RECORD_FOUND    => "A record matching %s was found",
    );
    /**
     * Table to work with.
     * @var string
     */
    private $_table = null;
    /**
     * Field to work with.
     * @var string
     */
    private $_field = null;
    
    public function __construct($table, $field)
    {
        $this->_table = $table;
        $this->_field = $field;
    }
    /**
     * Returns count for the value in the specified table/column
     * @param string $value
     * return int count for the value in the specified table/column
     */
    public function getCount($value) {
        return Doctrine_Query::create()
            ->from($this->_table)
            ->where("{$this->_field} = ?", $value)
            ->count();
    }
    
    /**
     * @param  string $messageKey
     * @param  string $value      OPTIONAL
     * @return void
     */
    protected function _error($messageKey, $value = null)
    {
        if ($value === null) {
            $value = $this->_value;
        }
        $this->_errors[] = $messageKey;
        $this->_messages[$messageKey] = sprintf($this->_messageTemplates[$messageKey], $value);
    }
}
