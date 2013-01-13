<?php
namespace \ZendMax;
class Adapter implements \Zend_Auth_Adapter_Interface
{
    const NOT_FOUND_MSG = "Usuario no encontrado";
    const BAD_PSW_MSG = "Clave invalida";
    const NOT_ACTIVE = "El usuario no ha sido activado";

    protected $user;
    protected $email;
    protected $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws \Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return \Zend_Auth_Result
     */
    public function authenticate()
    {
        try {
            $this->user = User::authenticate($this->email, $this->password);
            $result = $this->createResult(\Zend_Auth_Result::SUCCESS);
        } catch (Exception $e) {
            switch($e->getCode()) {
                case User::WRONG_PSW:
                    $result = $this->createResult(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::BAD_PSW_MSG);
                    break;
                case User::NOT_FOUND:
                    $result = $this->createResult(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::BAD_PSW_MSG);
                    break;
                case User::NOT_ACTIVE:
                    $result = $this->createResult(\Zend_Auth_Result::FAILURE_UNCATEGORIZED, self::NOT_ACTIVE);
                    break;
            }
            return $result;
        }
        return $result;
    }

    /**
     * Returns the autentication result.
     * @param int $code
     * @param array $messages
     * @return \Zend_Auth_Result
     */
    private function createResult($code, $messages = array())
    {
        if (!is_array($messages)) {
            $messages = array($messages);
        }
        return new \Zend_Auth_Result($code, $this->user, $messages);
    }
}