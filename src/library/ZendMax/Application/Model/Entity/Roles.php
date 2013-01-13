<?php

namespace Etermax\Application\Model\Entity;

/**
 * @Entity
 * @Table (name="acl_roles")
 */
class Roles extends \Etermax\Db\Doctrine\Doctrine
{

    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string" length=50 nullable=false) 
     */
    private $name;

    /**
     * @Column(type="string" length=1 nullable=true)  
     */
    private $parent_id;

    
}
