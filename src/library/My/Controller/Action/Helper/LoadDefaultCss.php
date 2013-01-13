<?php
/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Helper for adding default css to a page, the added js must be located in ${public}/css/${controller}/${action}.css
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @category   My
 * @package    My_Controller
 * @subpackage My_Controller_Action_Helper
 * @copyright  Copyright (c) 2011
 */
class My_Controller_Action_Helper_LoadDefaultCss extends Zend_Controller_Action_Helper_Abstract
{
    public function direct()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        $module = ($this->getRequest()->getModuleName() == 'default')? '':$this->getRequest()->getModuleName().'/';
        $file = '/css/'.$module.$this->getRequest()->getControllerName() .
            '/'.$this->getRequest()->getActionName().'.css';
        if(!file_exists(APPLICATION_PATH.'/../public'.$file)) {
            throw new My_Controller_Action_Helper_LoadDefaultCssExeption("File not found<br />$file");
        }
        $href = defined('APPLICATION_CDN')? APPLICATION_CDN:'';
        $href .= $file;
        $view->headLink()->appendStylesheet(
            $href
        );
    }
}
