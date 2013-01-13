<?php
namespace Etermax\Resource; 

class Mobile extends \Zend_Controller_Plugin_Abstract
{
    
    

    public function init()
    {

        $bootstrap = \Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $useragent = $bootstrap->getResource("useragent");
        $device = $useragent->getDevice();

        \Zend_Registry::set("useragent", $useragent);
        \Zend_Registry::set("device", $device);

        /**
         * @todo change this to be Mobile 
         */
        if ($device->getType() != "mobile") {

            /**
             * Set the layout to be mobile, here we make sure we streamline what is loaded
             * so as to not load things that arent needed.
             */
            \Zend_Layout::getMvcInstance()->setLayout("mobile_layout");

            /**
             * Here we check to see if a mobile version of the template exists.  if it does then we change the view suffix
             * this allows us to load the mobile view if it exists and the defgault view if it doesnt. 
             */
            $base = APPLICATION_PATH . "/views/scripts/";
            $mobile = $base . $request->getControllerName() . "/" . $request->getActionName() . ".mobile.phtml";

            if (is_readable($mobile)) {
                \Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->setViewSuffix('mobile.phtml');
            }
        }
    }

}