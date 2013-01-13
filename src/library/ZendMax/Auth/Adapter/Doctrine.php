<?php

namespace Etermax\Auth\Adapter;

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @see Doctrine_Connection
 */
require_once 'Doctrine/DBAL/Connection.php';

/**
 * @see Zend_Auth_Result
 */
require_once 'Zend/Auth/Result.php';

class Doctrine implements \Zend_Auth_Adapter_Interface
{

    /**
     * Database Connection
     *
     * @var Doctrine_Connection
     */
    protected $_conn = null;

    /**
     * $_tableName - the table name to check
     *
     * @var string
     */
    protected $_tableName = null;

    /**
     * $_identity - Identity value
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values
     *
     * @var string
     */
    protected $_credential = null;

    /**
     * __construct() - Sets configuration options
     *
     * @param  Doctrine_Connection      $zendDb
     * @param  string                   $tableName
     * @param  string                   $identityColumn
     * @param  string                   $credentialColumn
     * @param  string                   $credentialTreatment
     * @return void
     */
    public function __construct(\Doctrine\ORM\EntityManager $conn = null, $tableName = null)
    {
        if (null !== $conn) {
            $this->_conn = $conn;
        }

        if (null !== $tableName) {
            $this->_tableName = $tableName;
        }
    }

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to 
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {

        $results = $this->_conn->getRepository($this->_tableName)
            ->login($this->_identity, $this->_credential);
        
        $code = \Zend_Auth_Result::FAILURE;
        $identity = $this->_identity;
        if ($results) {
            $code = \Zend_Auth_Result::SUCCESS;
        }
        $result = new \Zend_Auth_Result($code, $identity);
        return $result;
    }

    public function setCredential($credential)
    {
        $this->_credential = $credential;
    }

    public function setIdentity($identity)
    {
        $this->_identity = $identity;
    }

}