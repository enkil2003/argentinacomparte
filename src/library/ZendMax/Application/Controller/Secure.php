<?php

namespace Etermax\Application\Controller;

class Secure extends Action
{

    protected $_user;
    
    protected $_secureException = array();

    public function init()
    {
        parent::init();

        try {
            /** If the action don't required secure  */
            $safe = in_array( $this->getRequest()->getActionName(), 
                $this->_secureException );
        
            if( $safe !== true ) {
                if( is_null( $this->_session->user ) ){
                    return $this->_redirect('/user/login/');
                }
                
                $auth = $this->_session->user->auth;
                if (!$auth->isValid()) {
                    throw new \Exception('You need login to see this area');
                }
            }
        } catch (\Exception $e) {
            return $this->_redirect('/user/login/');
        }
    }

    public function logoutAction()
    {
        \Zend_Auth::getInstance()->clearIdentity();
        $this->_session->user = null;
        return $this->_redirect('/');
    }

}
