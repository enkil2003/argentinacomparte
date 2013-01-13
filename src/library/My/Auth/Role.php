<?php
class My_Auth_Role
{
    const GUEST = 'guest';
    const USER = 'user';
    const ADMIN = 'admin';
    const SUPER = 'super';
     /**
     * Returns the ID of the Role
     * @param Zend_Auth $objAuth
     * @return int
     */
    static public function getUserRole()
    {
        $objAuth = Zend_Auth::getInstance();
        if($objAuth->hasIdentity()) {
            $identity = $objAuth->getIdentity();
            $role = $identity['Rol']['rol'];
        } else {
            $role = My_Auth_Role::GUEST;
        }
        return $role;
    }
}
