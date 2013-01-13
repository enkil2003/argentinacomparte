<?php

namespace Etermax\Application\Model\Entity;

/**
 * @Entity
 * @Table (name="us_users")
 */
class Users extends \Etermax\Db\Doctrine\Doctrine
{

    /**
     * @Id 
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string" length=150 nullable=false) 
     */
    private $username;

    /**
     * @Column(type="string" nullable=false) 
     */
    private $password;

    /**
     * @Column(type="datetime" nullable=false) 
     */
    private $created_at;

    /**
     * @Column(type="datetime" nullable=false) 
     */
    private $update_at;

    /**
     * @Column(type="string" length=1 nullable=false)  
     */
    private $status;

    /**
     * @PreUpdate 
     */
    public function prePersist()
    {
        $this->created_at = new DateTime();
        $this->update_at = new DateTime();
        if (is_null($this->status)) {
            $this->status = self::STATUS_DEFAULT;
        }

        $this->password = $this->_encrypt($this->password);
    }

    /**
     * @PreUpdate 
     */
    public function preUpdate()
    {
        $this->update_at = new DateTime();
    }

    public function getRandPassword($length = 7)
    {
        $password = "";
        $possible = "012346789abcdfghjkmnopqrstuvwxyzABCDFGHJKLMNPQRTVWXYZ$#!@.";
        $maxlength = strlen($possible);
        if ($length > $maxlength) {
            $length = $maxlength;
        }
        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

    private function _encrypt($password)
    {
        return crypt($password, "$1$" . substr(uniqid(), 0, 7) . "$");
    }

    const STATUS_DEFAULT = 'A';
}
