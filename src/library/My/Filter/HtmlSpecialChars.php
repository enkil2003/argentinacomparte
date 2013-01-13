<?php
require_once 'Zend/Filter/Interface.php';
class My_Filter_HtmlSpecialChars implements Zend_Filter_Interface
{
	/**
	 * tring $value Returns the modified string
	 * 
	 * @see Zend_Filter_Interface::filter()
	 * @TODO buscar una manera de poder pasar el segundo y cuarto parametro de htmlspecialchars
	 */
    public function filter($string)
    {
        $charset = APPLICATION_DEFAULT_ENCODING? APPLICATION_DEFAULT_ENCODING:'ISO8859-1';
        return htmlspecialchars($string, 2, $charset, true);
    }
}