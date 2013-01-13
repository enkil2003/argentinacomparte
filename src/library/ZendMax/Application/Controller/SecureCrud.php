<?php

namespace Etermax\Application\Controller;

class SecureCrud extends Crud
{

    protected $_username;

    public function init()
    {
        parent::init();
        try {
            $auth = \Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $this->_username = $auth->getIdentity();
            } else {
                throw new \Exception('You need login to see this area');
            }
        } catch (\Exception $e) {
            return $this->_redirect('/user/login/');
        }
    }

    public function logoutAction()
    {
        \Zend_Auth::getInstance()->clearIdentity();
        return $this->_redirect('/');
    }

}
