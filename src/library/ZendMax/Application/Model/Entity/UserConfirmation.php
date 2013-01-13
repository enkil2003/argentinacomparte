<?php

namespace Etermax\Application\Model\Entity;

/**
 * @Entity
 * @Table (name="us_users_confirmations")
 */
class UserConfirmations extends \Etermax\Db\Doctrine\Doctrine
{

    /**
     * @Id 
     * @Column(type="integer")
     */
    private $user_id;

    /**
     * @Column(type="string" nullable=false) 
     */
    private $code;

    /**
     * @Column(type="datetime" nullable=false) 
     */
    private $created_at;

    /**
     * @PreUpdate 
     */
    public function prePersist()
    {
        $this->created_at = new DateTime();
    }

}
