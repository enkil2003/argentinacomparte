<?php
namespace ZendMax\Db\Doctrine;

abstract class Mapper
{
    
    protected $_em;
    protected $_config;

	public function __construct()
	{
        if( ! \Zend_Registry::isRegistered( 'doctrine') ){
            throw new \Exception('You need use doctrine connection');
        }

            $this->_em = \Zend_Registry::get( 'doctrine') ;
            
            if( ! \Zend_Registry::isRegistered( 'config') ){
                throw new \Exception('You need use config');
            }

            $this->_config = \Zend_Registry::get( 'config') ;
                        
            $this->init();
            
	}
    
    public function urlFormat( $url )
    {
        $url = trim( $url );
        // Tranformamos todo a minusculas
        $url = strtolower($url);
        // Añaadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace ($find, '-', $url);
        //Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace ($find, $repl, $url);
        // Eliminamos y Reemplazamos demás caracteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
        return $url;        
    }

	public function init()
	{
	}
}

