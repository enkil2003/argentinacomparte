<?php

class Etermax_Controller_Action_Helper_EtermaxFlashMessenger extends Zend_Controller_Action_Helper_FlashMessenger
{

    public function addFormErrors ($form)
    {
        foreach( $form->getErrors() as $element => $error ){
            if( count( $error )) {
                foreach( $error as $message ){
                    $this->addMessage('{error} ' . $element .' ' . $message );                                
                }
            }
        
        }
        
        return $this;
    }

}
