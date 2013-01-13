<?php
namespace ZendMax;
class Role
{
    const GUEST = 'guest';
    const USER = 'user';
    const ADMIN = 'admin';
    const SUPER = 'super';
     /**
     * Returns the ID of the Role
     * @param \Zend_Auth $objAuth
     * @return int
     */
    static public function getUserRole()
    {
        $objAuth = \Zend_Auth::getInstance();
        if($objAuth->hasIdentity()) {
            $identity = $objAuth->getIdentity();
            $role = $identity['Rol']['rol'];
        } else {
            $role = self::GUEST;
        }
        return $role;
    }
}