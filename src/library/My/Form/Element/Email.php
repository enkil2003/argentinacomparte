<?php
/** Zend_Form_Element_Submit */
require_once 'Zend/Form/Element/Text.php';

/**
 * Button form element
 *
 * @category   My
 * @package    My_Form
 * @subpackage Element
 */
class My_Form_Element_Email extends Zend_Form_Element_Xhtml
{
    /**
     * Use formElement view helper by default
     * @var string
     */
    public $helper = 'formEmail';
}
