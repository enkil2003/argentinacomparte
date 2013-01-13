<?php
namespace ZendMax;
class Form extends \Zend_Form
{
    /**
     * Constructor
     *
     * Registers form view helper as decorator
     *
     * @param mixed $options
     * @return void
     */
    public function __construct($options = null)
    {
        $this->addPrefixPath(
            '\ZendMax\Form\Decorator\\',
            'ZendMax/Form/Decorator/',
            \Zend_Form::DECORATOR
        );
        $this->addPrefixPath(
            '\ZendMax\Form\Decorator\\',
            'ZendMax/Form/Decorator/',
            \Zend_Form::DECORATOR
        );
        $this->addPrefixPath(
            '\ZendMax\Form\Element\\',
            'ZendMax/Form/Element/',
            \Zend_Form::ELEMENT
        );
        $this->addElementPrefixPath(
            '\ZendMax\Filter\\',
            'ZendMax/Filter/',
            'filter'
        );
        $this->addElementPrefixPath(
            '\ZendMax\Validate\\',
            'ZendMax/Validate/',
            'validate'
        );
        $this->addElementPrefixPath(
            '\ZendMax\Form\Decorator\\',
            'ZendMax/Form\Decorator/',
            'decorator'
        );
        parent::__construct($options);
    }
}
