<?php

namespace Etermax\Application\Model\Entity;

/**
 * @Entity
 * @Table (name="acl_users_roles")
 */
class UsersRoles extends \Etermax\Db\Doctrine\Doctrine
{

    /**
     * @Id 
     * @Column(type="integer")
     */
    private $user_id;
    
    
    /**
     * @Id 
     * @Column(type="integer")
     */
    private $role_id;
   
}
