<?php
/**
 * Description of Project_Auth_AclInit
 *
 * @author Jotag
 */
class My_Auth_Acl extends Zend_Acl
{
    /**
     * Acl Constructor
     * @TODO once bussines logic is defined, let's put some dynamic code
     */
    public function __construct()
    {
        // Add roles
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'user');
        $this->addRole(new Zend_Acl_Role('super'), 'admin');
        
        // Add a resource
        $this->add(new Zend_Acl_Resource('default'));
        $this->add(new Zend_Acl_Resource('user'));
        $this->add(new Zend_Acl_Resource('admin'));
        $this->add(new Zend_Acl_Resource('cart'));
        
        // privileges
        $this->allow('guest', 'default');
        $this->allow('user', 'user');
        $this->allow('guest', 'cart');
        $this->deny('guest', 'user');
        // exceptions on user controller for guests
        $this->allow(
            'guest',
            'user',
            array(
                'login',
                'ajax-login',
                'password-reset',
                'reset-password-confirmation',
                'mailchimp',
                'confirmation',
                'register',
                'registration-complete',
                'activate-user'
            )
        );
        $this->allow('admin', 'admin');
    }
    /*
    public function __construct()
    {
        $rolesArray = Doctrine_Query::create()
            ->from('Rol')
            ->orderBy('id')
            ->fetchArray();
        $roles = array();
        foreach ($rolesArray as $r) {
            $roles[$r['id']] = $r['rol']; 
        }
        //Add roles
        foreach ($rolesArray as $r) {
            if(!$this->hasRole($r['rol'])) {
                $this->addRole(new Zend_Acl_Role($r['rol']), $roles[$r['inhrit']]);
            }
        }
        //Add a resource
        $privileges = Doctrine_Query::create()
            ->select('p.resource, r.rol role, res.resource')
            ->from('Privilege p')
            ->innerJoin('p.Rol r')
            ->innerJoin('p.Resource res')
            ->orderBy('r.Rol')
            ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        foreach($privileges as $privilege) {
            if (!$this->has($privilege['Resource']['resource'])) {
                $this->add(new Zend_Acl_Resource($privilege['Resource']['resource']));
            }
        }
        foreach($privileges as $privilege) {
            $this->allow($roles[$privilege['rol']], $privilege['Resource']['resource']);
        }
    }
    */
}
