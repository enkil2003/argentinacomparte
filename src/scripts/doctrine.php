<?php

use Doctrine\ORM\Mapping as ORM;

//taking the command option --env
$env = null;
$argv =& $_SERVER['argv'];
for($i = 0; $i < count($argv) ;$i++){
	$pos = strpos($argv[$i], '--env=');
	if($pos !== false){
		$env = substr($argv[$i], strlen('--env='));
		unset($argv[$i]);
		break;
	}
}
switch($env){
	case 'local':
	case 'development':
	case 'staging':
	case 'production':
		break;
	default:
		echo "--env=ENVIRONMENT option is required, valid values are:\n";    
		echo "local\n";
		echo "development\n";
		echo "staging\n";
		echo "production\n";
		exit;
		break;
}
$argv = array_values($argv);

define('APPLICATION_ENV', $env);
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
    
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';
// Creating application
$application = new \Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

require_once APPLICATION_PATH . '/../library/ZendMax/Resource/Autoload.php';
$bootstrap = $application->bootstrap('autoload')
                         ->bootstrap('Doctrine')
                         ->bootstrap();

// Retrieve Doctrine Container resource
$container = $application->getBootstrap()->getResource('doctrine');

// Console
$cli = new \Symfony\Component\Console\Application(
    'Doctrine Command Line Interface',
    \Doctrine\Common\Version::VERSION
);

try {
    // Bootstrapping Console HelperSet
    $helperSet = array();
    if (($dbal = $container->getConnection(getenv('CONN') ?: $container->defaultConnection)) !== null) {
        $helperSet['db'] = new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($dbal);
    }

    if (($em = $container->getEntityManager(getenv('EM') ?: $container->defaultEntityManager)) !== null) {
        $helperSet['em'] = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em);
    }
} catch (\Exception $e) {
    $cli->renderException($e, new \Symfony\Component\Console\Output\ConsoleOutput());
}

$cli->setCatchExceptions(true);
$cli->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet($helperSet));

$cli->addCommands(array( 
    // DBAL Commands
    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

    // ORM Commands
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),

));

$cli->run();
