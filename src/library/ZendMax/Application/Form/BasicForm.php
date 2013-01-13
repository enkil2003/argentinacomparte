<?php
namespace Etermax\Application\Form;
class BasicForm extends \Etermax\Form
{
    public function init()
    {
        $element = new \Zend_Form_Element_Submit( 'accept' );
        $element->setDecorators( array(
            'ViewHelper',
            'Errors'
        ) );
        $this->addElement( $element );

        $element = new \Zend_Form_Element_Button( 'cancel' );
        $element->setDecorators( array(
                        'ViewHelper',
                        'Errors'
            ) );
        $this->addElement( $element );

        $this->addDisplayGroup( array( 'accept',
                        'cancel'
            ), 'Menu' );
        // $todo: reveer esto.
        $this->Menu->setOrder(100000);
        $this->setMethod( 'post' );
    }
}