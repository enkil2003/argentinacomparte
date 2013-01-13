<?php

class Etermax_Controller_Action_Helper_ServerTester extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * list the server methods.
     */
    public function listAction ($serverClass, $action)
    {

        if (is_object($serverClass)) {
            $action->view->className = get_class($serverClass);
        } else {
            $action->view->className = $serverClass;
        }
        
        
        
        try {
            $class = Zend_Server_Reflection::reflectClass($serverClass);
        } catch (Exception $e) {
            echo $serverClass . "<br>";
            echo $e->getMessage();
            exit();
        
        }
        
        $action->view->methods = array();
        foreach ($class->getMethods() as $func) {
            $i = 1;
            $prototypes = $func->getPrototypes();
            foreach ($prototypes as $prototype) {
                $parameters = $prototype->getParameters();
                $params = array();
                foreach ($parameters as $parameter) {
                    $params[$parameter->getName()] = $parameter->getType();
                }
                $method = array();
                $method['name'] = $func->getName();
                $method['type'] = $prototype->getReturnType();
                $method['itype'] = $i ++;
                $method['params'] = $params;
                $action->view->methods[] = $method;
            }
        }
    }

    /**
     * edit form
     */
    public function editAction ($serverClass, $action)
    {

        /*if ( ! $action->isInDebugMode() )  {
            throw new Exception( "si claro" );
        }*/
        
        $method = $action->getRequest()->getParam('method');
        if (empty($method)) {
            die("Method undeclared");
            $action->_redirect(
                $action->view->url(array("action" => 'list' )));
            return;
        }
        $type = $action->getRequest()->getParam('itype');
        if (empty($type) || ! is_numeric($type)) {
            die("Type undeclared");
            $action->_redirect(
                $action->view->url(array("action" => 'list' )));
            return;
        }
        
        if (is_object($serverClass)) {
            $action->view->className = get_class($serverClass);
        } else {
            $action->view->className = $serverClass;
        }
        $class = Zend_Server_Reflection::reflectClass($serverClass);
        $methods = $class->getMethods();
        $func = null;
        foreach ($methods as $f) {
            if ($f->getName() == $method) {
                $func = $f;
                break;
            }
        }
        if (empty($func)) {
            die("Method $method invalid");
            $action->_redirect(
                $action->view->url(array("action" => 'list' )));
            return;
        }
        $prototypes = $func->getPrototypes();
        $prototype = $prototypes[$type - 1];
        if (empty($prototype)) {
            die("Type invalid");
            $action->_redirect(
                $action->view->url(array("action" => 'list' )));
            return;
        }
        $method = array();
        $method['name'] = $func->getName();
        $method['type'] = $prototype->getReturnType();
        $action->view->method = $method;
        
        $action->view->form = new \Etermax\Form\BasicForm();
        $action->view->form->addElement('checkbox', 'check_type');
        $action->view->form->check_type->setLabel("Check parameters type");
        $action->view->form->check_type->setValue(true);
        
        $action->view->form->addElement('select', 'client');
        $action->view->form->client->setLabel("Client Type");
        // @todo: Completar esto
        $action->view->form->client->addMultiOptions(
            array("soap" => "Soap", "json" => "Json", "rest" => "Rest", 
                "xmlrpc" => "Xml Rpc" ));
        
        $action->view->form->cancel->setLabel('Cancelar')->setAttrib('onclick', 
            "document.location='" .
                 $action->view->url(array('action' => 'list' )) . "'");
        
        $parameters = $prototype->getParameters();
        foreach ($parameters as $parameter) {
            $pName = $parameter->getName();
            $pType = $parameter->getType();
            $element = new Zend_Form_Element_Text($pName);
            $element->setLabel($pName . "( " . $pType . " )");
            $action->view->form->addElement($element);
        }
        
        $data = $action->getRequest()->getParams();
        if ($action->getRequest()->isPost()) {
            $isValid = $action->view->form->isValid($data);
            if ($isValid) {
                try {
                    $args = array();
                    foreach ($parameters as $parameter) {
                        $pName = $parameter->getName();
                        $pType = $parameter->getType();
                        if ($data['check_type']) {
                            switch ($pType) {
                                case "integer":
                                    if (! is_numeric($data[$pName])) {
                                        throw new Exception(
                                            "Invalid type data for field $pName");
                                    }
                                    $args[] = intval($data[$pName]);
                                    break;
                                case "string":
                                    $args[] = $data[$pName];
                                    break;
                                case "double":
                                    $args[] = $data[$pName];
                                    break;
                                case "mixed":
                                    $args[] = $data[$pName];
                                    break;
                                default:
                                    throw new Exception("Invalid type $pType");
                                    break;
                            }
                        } else {
                            $args[] = $data[$pName];
                        }
                    }
                    switch ($data["client"]) {
                        case "soap":
                            $this->soapClient($action, $func->getName(), $args);
                            break;
                        case "json":
                            $this->jsonClient($action, $func->getName(), $args);
                            break;
                        case "rest":
                            $this->restClient($action, $func->getName(), $args);
                            break;
                        case "xmlrpc":
                            $this->xmlRpcClient($action, $func->getName(), 
                                $args);
                            break;
                    }
                } catch (Exception $e) {
                    $action->view->retval = "Exception ( " . $e->__toString() .
                         " ): " . $e->getMessage();
                    }
                }
            }
            $action->view->form->setAction($action->view->url());
            $action->view->form->populate($data);
        }

        public function soapClient ($action, $name, $args)
        {

            $client = new Zend_Soap_Client($action->getSoapWsdlUrl());
            try {
                $r = call_user_func_array(array($client, $name ), $args);
                $action->view->retval = Zend_Debug::dump($r, null, false);
            } catch (Exception $e) {
                $action->view->extra = $client->getLastResponse();
                throw $e;
            }
        }

        public function jsonClient ($action, $name, $args)
        {

            $client = new Zend_Http_Client();
            try {
                // ENCODE JSON !!!
                //$r = call_user_func_array( array( $client, $func->getName()), $args );
                //$action->view->retval = Zend_Debug::dump( $r, null, false );
                $client->setRawData(
                    $xml, 'text/json')->request('POST');
            
            } catch (Exception $e) {
                $action->view->extra = $client->getLastResponse();
                throw $e;
            }
        }

        public function restClient ($action, $name, $args)
        {

            $client = new Zend_Rest_Client($action->getRestUrl());
            try {
                $r = call_user_func_array(array($client, $name ), $args);
                $action->view->retval = Zend_Debug::dump($r->get(), null, false);
            } catch (Exception $e) {
                $action->view->extra = $e->__toString() . " " . $e->getMessage();
                throw $e;
            }
        }

        public function xmlRpcClient ($action, $name, $args)
        {

            $client = new Zend_XmlRpc_Client($action->getXmlRpcUrl());
            try {
                $r = $client->call($name, $args);
                $action->view->retval = Zend_Debug::dump($r, null, false);
            } catch (Exception $e) {
                $action->view->extra = $client->getHttpClient()
                    ->getLastResponse()
                    ->getBody();
                throw $e;
            }
        }
    
    }