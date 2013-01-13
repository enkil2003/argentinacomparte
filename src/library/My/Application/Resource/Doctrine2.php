<?php
class My_Application_Resource_Doctrine2 extends Zend_Application_Resource_ResourceAbstract
{
	/** @TODO sacar estas cosas hardcodeadas */
        // Default array of options that can be overridden using the application.ini file.
        // e.g., resources.entityManager.autoGenerateProxyClasses = false
    protected $_options = array(
        'connection' => array(
            'driver' => 'pdo_mysql',
            'host' => '127.0.0.1',
            'dbname' => 'prueba',
            'user' => 'root',
            'password' => 'qwerty'
        ),
        'modelDir' => '/models',
        'proxyDir' => '/proxies',
        'proxyNamespace' => 'Proxies',
        'autoGenerateProxyClasses' => true,
        'common' => array(
            'classLoader' => 'Doctrine',
            'path' => '../library/'
        )
    );

    public function init()
    {
        return $this->_injectDoctrine();
    }
    
    private function _injectDoctrine()
    {
        $options = $this->getOptions();
        // include and register Doctrine's class loader
        require_once('Doctrine/Common/ClassLoader.php');
        $classLoader = new \Doctrine\Common\ClassLoader(
            $options['common']['classLoader']['name'], 
            $options['common']['classLoader']['path'] 
        );
        $classLoader->register();
        // create the Doctrine configuration
        $config = new \Doctrine\ORM\Configuration();
 
        // setting the cache ( to ArrayCache. Take a look at
        // the Doctrine manual for different options ! )
        $cache = new \Doctrine\Common\Cache\ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
 
        // choosing the driver for our database schema
        // we'll use annotations
        $driver = $config->newDefaultAnnotationDriver(
            $options['path']['defaultAnnotationDriver']
        );
        $config->setMetadataDriverImpl($driver);
 
        // set the proxy dir and set some options
        $config->setProxyDir($options['path']['proxy']);
        $config->setAutoGenerateProxyClasses($options['autoGenerateProxyClasses']);
        $config->setProxyNamespace($options['proxyNamespace']);
 
        // now create the entity manager and use the connection
        // settings we defined in our application.ini
        $conn = array(
            'driver'    => $options['connection']['driver'],
            'user'      => $options['connection']['user'],
            'password'  => $options['connection']['password'],
            'dbname'    => $options['connection']['dbname'],
            'host'      => $options['connection']['host']
        );
        $entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
 
        // push the entity manager into our registry for later use
        $registry = Zend_Registry::getInstance();
        $registry->entitymanager = $entityManager;
 
        return $entityManager;
    }
}
