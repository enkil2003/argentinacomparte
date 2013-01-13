<?php

namespace Etermax\Application\Model\Entity;

/**
 * @Entity
 * @Table (name="acl_rules")
 */
class Rules extends \Etermax\Db\Doctrine\Doctrine
{

    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="integer" nullable=false) 
     */
    private $roles_id;
    
    /**
     * @Column(type="integer" nullable=false) 
     */
    private $resource_id;
    
    /**
     * @Column(type="string" length=50 nullable=false) 
     */
    private $action;
    
}
