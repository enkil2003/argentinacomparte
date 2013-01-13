<?php
namespace Etermax\Application\Controller;

class Server extends Action
{

    protected $_serverClass = null;

    protected $_serverClassArgs = null;

    protected $_aliasClass = null;

    protected $_serverDirectory = null;

    protected $_classMap = array();

    public function getClass ()
    {

        return $this->_serverClass;
    }

    public function setClass ($_serverClass, $argv = null)
    {

        $this->_serverClass = $_serverClass;
        if (1 < func_num_args()) {
            $argv = func_get_args();
            array_shift($argv);
            $this->_serverClassArgs = $argv;
        }
    }

    public function init ()
    {

        parent::init();
        if (\Zend_Controller_Action_HelperBroker::hasHelper('layout')) {
            $this->_helper->layout->disableLayout();
        }
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * AMF server action.
     *
     * @todo add support for addDirectory in the other servers (if it is possible)
     */
    public function amfAction ()
    {

        $server = new \Zend_Amf_Server();
        if (! empty($this->_serverClassArgs)) {
            $args = array_merge(array($this->_serverClass, '' ), 
                $this->_serverClassArgs);
            call_user_func_array(array($server, 'setClass' ), $args);
        } else {
            $server->setClass($this->_serverClass);
        }
        if (! empty($this->_aliasClass)) {
            $server->setClassMap($this->_aliasClass, $this->_serverClass);
        }
        if (is_array($this->_classMap) && count($this->_classMap) > 0) {
            foreach ($this->_classMap as $phpclass => $asclass)
                $server->setClassMap($asclass, $phpclass);
        }
        if ($this->isInDebugMode()) {
            $server->setProduction(false);
        }
        
        echo $server->handle();
    
     //throw new Zend_Amf_Server_Exception("Access not allowed");
    }

    /**
     * SOAP server action.
     */
    public function soapAction ()
    {

        if (isset($_GET['wsdl'])) {
            $wsdl = new \Zend_Soap_AutoDiscover();
            $wsdl->setClass($this->_serverClass);
            $wsdl->handle();
        } else {
            $wsdlUrl = $this->getSoapWsdlUrl();
            $server = new \Zend_Soap_Server($wsdlUrl);
            if ($this->isInDebugMode()) {
                $server->registerFaultException('Exception');
            }
            $server->registerFaultException('Exts_Exception');
            if (! empty($this->_serverClassArgs)) {
                $args = array_merge(array($this->_serverClass ), 
                    $this->_serverClassArgs);
                call_user_func_array(array($server, 'setClass' ), $args);
            } else {
                $server->setClass($this->_serverClass);
            }
            $server->handle();
        }
    }

    public function getSoapWsdlUrl ()
    {

        $scheme = $this->getFrontController()
            ->getRequest()
            ->getScheme();
        $host = $this->getFrontController()
            ->getRequest()
            ->getHttpHost();
        return "$scheme://$host" . $this->_helper->url('soap') . "?wsdl";
    }

    public function jsonAction ()
    {

        $server = new Zend_Json_Server();
        if (! empty($this->_serverClassArgs)) {
            $args = array_merge(array($this->_serverClass, '' ), 
                $this->_serverClassArgs);
            call_user_func_array(array($server, 'setClass' ), $args);
        } else {
            $server->setClass($this->_serverClass);
        }
        $response = $server->handle();
        echo $response;
    }

    // servidor XMLRPC
    public function xmlrpcAction ()
    {

        $server = new \Etermax_Server_XmlRpc();
        if (! empty($this->_serverClassArgs)) {
            $args = array_merge(array($this->_serverClass, '' ), 
                $this->_serverClassArgs);
            call_user_func_array(array($server, 'setClass' ), $args);
        } else {
            $server->setClass($this->_serverClass);
        }
        if ($this->isInDebugMode()) {
            \Zend_XmlRpc_Server_Fault::attachFaultException('Exception');
        }
        \Zend_XmlRpc_Server_Fault::attachFaultException('Exts_Exception');
        $response = $server->handle();
        echo $response;
    }

    public function getXmlRpcUrl ()
    {

        $scheme = $this->getFrontController()
            ->getRequest()
            ->getScheme();
        $host = $this->getFrontController()
            ->getRequest()
            ->getHttpHost();
        return "$scheme://$host" . $this->_helper->url('xmlrpc');
    }

    // servidor REST
    public function restAction ()
    {

        $server = new \Zend_Rest_Server();
        if (! empty($this->_serverClassArgs)) {
            $args = array_merge(array($this->_serverClass, '' ), 
                $this->_serverClassArgs);
            call_user_func_array(array($server, 'setClass' ), $args);
        } else {
            $server->setClass($this->_serverClass);
        }
        
        $server->handle();
    }

    public function getRestUrl ()
    {

        $scheme = $this->getFrontController()
            ->getRequest()
            ->getScheme();
        $host = $this->getFrontController()
            ->getRequest()
            ->getHttpHost();
        return "$scheme://$host" . $this->_helper->url('rest');
    }

    /**
     * list the server methods.
     */
    public function listAction ()
    {

        $this->_helper->viewRenderer->setNoRender(false);
        $this->_helper->viewRenderer->setViewScriptPathSpec("server/list.phtml");
        $this->_helper->serverTester->listAction($this->_serverClass, $this);
    }

    /**
     * edit form
     */
    public function editAction ()
    {

        /*       if ( ! $this->isInDebugMode() )  {
            throw new Exception( "si claro" );
        }*/
        $this->_helper->viewRenderer->setNoRender(false);
        $this->_helper->viewRenderer->setViewScriptPathSpec("server/edit.phtml");
        $this->_helper->serverTester->editAction($this->_serverClass, $this);
    }

    /**
     * document server methods in WIKI format
     */
    public function documentAction ()
    {

        $this->_helper->viewRenderer->setNoRender(false);
        
        $viewType = 'wiki';
        if ($this->getRequest()->getParam('type')) {
            $viewType = $this->getRequest()->getParam('type');
            if ($viewType != 'xml' && $viewType != 'html' && $viewType != 'wiki') {
                $viewType = 'wiki';
            }
        }
        
        $this->_helper->viewRenderer->setViewScriptPathSpec(
            "server/$viewType.phtml");
        $result = $this->_helper->serverDocumentator->generateDoc(
            $this->_serverClass, $this->_aliasClass, $this->view);
        
        $this->view->classInfo = $result;
    }
}