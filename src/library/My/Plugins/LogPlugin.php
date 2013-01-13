<?php
/**
 * Set log
 *
 * @author Jotag
 */
class Project_Plugins_LogPlugin extends Zend_Controller_Plugin_Abstract
{

    public function  dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {

        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam("bootstrap");

        $logger = $bootstrap->getResource('Log');

        $module = $this->getRequest()->getParam('module');
	$controller = $this->getRequest()->getParam('controller');
	$action = $this->getRequest()->getParam('action');
        $logger->setEventItem('module', $module)
                ->setEventItem('controller', $controller)
                ->setEventItem('action', $action);

        Zend_Registry::set('logger', $logger);
    }
}
?>
