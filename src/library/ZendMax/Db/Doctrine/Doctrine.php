<?php
namespace ZendMax\Db\Doctrine;

abstract class Doctrine
{

    protected $_em;

    public function __construct()
    {
        
        $registry =  \Zend_Registry::getInstance();
        
        if (!\Zend_Registry::isRegistered('doctrine')) {
            throw new Exception('You need save Entity Manager into doctrine');
        }
        $this->_em = \Zend_Registry::get('doctrine');
        
        
    }

    public function __get($name)
    {

        if (isset($this->{$name})) {
            return $this->{$name};
        }
        throw new Exception("The variable $this->{$name} not was found  ");
    }

    public function fetchAll()
    {

        $results = $this->_em->createQuery('select c from Application_Model_Categories c');
        return $results->iterate();
    }

    /**
     * Persist a row into db
     *
     * @param mixed $bind 
     */
    public function create($bind)
    {
        foreach ($bind as $col => $value) {
            try {
                $this->{$col} = $value;
            } catch (\Exception $e) {
                throw new Exception('Was not Found the key '. $name);
            }
        }
        $this->_em->persist($this);
        $this->_em->flush();
    }

    /**
     * Return a row like an array
     */
    public function toArray()
    {
        return (array) $this;
    }

    
}
