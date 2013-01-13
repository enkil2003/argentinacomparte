<?php
/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Helper for adding default js to a page, the added js must be located in ${public}/js/${controller}/${action}.js
 * Also it is possible to define a APPLICATION_CDN having available a costant with that name
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @category   My
 * @package    My_Controller
 * @subpackage My_Controller_Action_Helper
 * @copyright  Copyright (c) 2011
 */
class My_Controller_Action_Helper_LoadDefaultJs extends Zend_Controller_Action_Helper_Abstract
{
    public function direct()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        $module = ($this->getRequest()->getModuleName() == 'default')? '':$this->getRequest()->getModuleName().'/';
        $file = '/js/'.$module.$this->getRequest()->getControllerName() .
            '/'.$this->getRequest()->getActionName().'.js';
        if(!file_exists(APPLICATION_PATH.'/../public'.$file)) {
            throw new My_Controller_Action_Helper_LoadDefaultJsExeption("File not found<br />$file");
        }
        $view->minifyFooterScript()->appendFile(
            $file
        );
    }
}
