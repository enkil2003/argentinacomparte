<?php

namespace Etermax\Application\Model\Entity;

/**
 * @Entity
 * @Table (name="acl_resources")
 */
class Resources extends \Etermax\Db\Doctrine\Doctrine
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

   
}
