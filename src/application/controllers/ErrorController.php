<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        
        if (APPLICATION_ENV != 'production') {
            $this->view->layout()->disableLayout();
        }
        $errors = $this->_getParam('error_handler');
        
        
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        $bootstrap = $this->getInvokeArg('bootstrap');//////
        $environment = $bootstrap->getEnvironment();///////
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                
                if ($environment == 'staging')
                {$this->_redirect('/error');}
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';

                if ($environment == 'staging')
                {$this->_redirect('/error');}
                break;
        }
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $when = date('Y-m-d h:g:i');
            $params = print_r($errors->request->getParams(), true);
            $message = <<<MESSAGE

--------------------------------
{$when} Ocurrio un error:
{$this->view->message}

Parametros del request:
{$params}

MESSAGE;
            $log->log(
                $message,
                $priority,
                $errors->exception
            );
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
    
    public function indexAction()
    {
        //
    }


}
