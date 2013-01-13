<?php

class Etermax_View_Helper_FlashMessenger extends Zend_View_Helper_Abstract
{

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    private $_flashMessenger = null;
    private $_messages = array();

    /**
     * Display Flash Messages.
     *
     * @param  string $key Message level for string messages
     * @param  string $template Format string for message output
     * @return string Flash messages formatted for output
     */
    public function flashMessenger()
    {
        $this->_getFlashMessenger();

        //get messages from previous requests
        $messages = $this->_flashMessenger->getCurrentMessages();

        if (count($messages)) {

            foreach ($messages as $m) {
                try{
                    $pattern = '/\{(.*)\}(.*)/';
                    preg_match($pattern, $m, $method);
                    $this->_messages[$method[1]][] = $method[2];
                }catch(\Exception $e ){
                    continue;
                }
            }
        }
        return $this;
    }
    
    public function __toString()
    {
        $this->view->flashMessages = $this->_messages;
        return $this->view->render( 'messages/render.phtml');
           
    }


    /**
     * Lazily fetches FlashMessenger Instance.
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function _getFlashMessenger()
    {
        if (null === $this->_flashMessenger) {
            $this->_flashMessenger =
                Zend_Controller_Action_HelperBroker::getStaticHelper(
                    'FlashMessenger'
            );
        }
        return $this->_flashMessenger;
    }

}