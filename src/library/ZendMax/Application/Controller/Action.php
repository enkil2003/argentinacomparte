<?php

namespace Etermax\Application\Controller;

class Action extends \Zend_Controller_Action
{
    protected $_em;
    protected $_request;
    protected $_session;
    protected $_messages;
    protected $_config;

    public function init()
    {
        /**
         * @todo add this action to application.ini and use namespaces
         */
        \Zend_Controller_Action_HelperBroker::addPath(
        	__DIR__ . '/Helper', 
            'Etermax_Controller_Action_Helper_'
        );

        $this->view->addBasePath(__DIR__ . '/../View/');

        /**
         * PROVISIONAL, hay que mudarlo al application.ini
         */
        $this->view->addHelperPath(
        	__DIR__ . '/../View/Helper', 'Etermax_View_Helper'
        );

        $this->_request = $this->getRequest();
        $this->_em = \Zend_Registry::get('doctrine');

        $this->_messages = $this->_helper->getHelper('EtermaxFlashMessenger');

        $this->_initSession();
        
        $this->_initUser();

        $this->_initConfig();
    }

    private function _initUser()
    {
        if( !isset( $this->_session->user->auth )){
            return false;
        }
        $auth = $this->_session->user->auth;
        
        if ($auth->isValid()) {
            $this->_user->auth = $this->_session->user;
            \Zend_Registry::set('user', $this->_user->auth);
        }
    }

    private function _initConfig()
    {
        if(!$this->_request->isXmlHttpRequest()){
    		$this->_config = \Zend_Registry::get('config');
        	$this->view->config = $this->_config;
        }
    }

    private function _initSession()
    {
        $namespace = new \Zend_Session_Namespace('ETERMAX');
        $this->_session = $namespace;
    }

}

