<?php
class My_Plugins_AclPlugin extends Zend_Controller_Plugin_Abstract
{
    const ACL_SESSION_NAME = 'Vassilymas_Acl';
    const LOGIN_ACTION = 'login';
    const LOGIN_CONTROLLER = '';
    private static $_objAclSession;

    /**
     * Retrieves the ACL from session or creates a new one.
     * @return Zend_Acl
     */
    static public function _getAcl() {
        $_acl = NULL;
        self::$_objAclSession = new Zend_Session_Namespace(self::ACL_SESSION_NAME);
        if (isset(self::$_objAclSession->acl)){
            $_acl = self::$_objAclSession->acl;
        } else {
            $_acl = new My_Auth_Acl();
            self::_saveAclToSession($_acl);
        }
        return $_acl;
    }

    static public function _clearSession() {
        $_acl = new My_Auth_Acl();
        self::_saveAclToSession($_acl);
    }

    private static function _saveAclToSession($_acl) {
        self::$_objAclSession->acl = $_acl;
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        //Authed users and role user
        $role = My_Auth_Role::getUserRole(Zend_Auth::getInstance());
        $objAcl = self::_getAcl();
        //Get resource
        $module = ($module = $request->getModuleName())? $module:'default';
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $resource = $controller;
        if (!$objAcl->has($resource)){
            $resource = $module;
        }
        $is_allowed = $objAcl->isAllowed($role, $resource, $action);
        // If the user is logged in and allowed, we don't want to show the login
        // de aclMemory puedo hacer una clase
        $aclMemory = new Zend_Session_Namespace(self::ACL_SESSION_NAME);
        if ($module == 'admin') {
            if (Zend_Auth::getInstance()->hasIdentity() && $is_allowed) {
                if ($request->getActionName() == 'login') {
                    $request->setModuleName('admin')
                        ->setControllerName('index')
                        ->setActionName('index');
                }
            } else {
                // If they aren't, redirect to the login form
                $request->setModuleName('default')
                    ->setControllerName('user')
                    ->setActionName('login');
            }
        } else {
            if (!$is_allowed) {
                // el acl memory guarda el request original
                if (!$aclMemory->request) {
                    $aclMemory->request['uri'] = $_SERVER['REQUEST_URI'];
                    // reutiliza el request en memoria para simularlo, 
                    $aclMemory->request['object'] = serialize($request);
                    // sea por get o post
                    $aclMemory->request['method'] = $request->isPost()? 'POST':'GET';
                    $aclMemory->request['params'] = $request->getParams();
                    $aclMemory->request['action'] = $action;
                    $aclMemory->request['controller'] = $controller;
                }
                // luego te reenvia al login
                Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')
                    ->gotoSimpleAndExit(self::LOGIN_ACTION, self::LOGIN_CONTROLLER);
            } else {
                // y una vez finalizado el login correcto
                // me fijo si tengo memoria y si ya me loguee
                if ($aclMemory->request && Zend_Auth::getInstance()->hasIdentity()) {
                    // si estoy recordando algo
                    $_SERVER['REQUEST_METHOD'] = $aclMemory->request['method'];
                    if ($request->isPost()) {
                        $_POST = $aclMemory->request['params'];
                    } else {
                        $_GET[] = $aclMemory->request['params'];
                    }
                    $aclMemory->unsetAll();
                    unset($aclMemory);
                }
            }
        }
    }
}
