<?php
try {
    //define('SRC_PATH', );
    define('SRC_PATH', dirname(dirname(dirname(__FILE__))) .'/trunk' );
    
    // prepare library and autoload
    set_include_path(
        SRC_PATH . "/library"
    );
    function __autoload($class) {
        require_once str_replace("_", "/", $class) . ".php";
    }
    
    // read configuration file
    $config = new Zend_Config_Ini('config.ini');
    
    // find installation path
    $installationPath = realpath(dirname(dirname(dirname(__FILE__))));
    
    // set configuration variables
    $vhostLocation = $config->vhostLocation;
    $hostFileLocation = $config->hostFileLocation;
    $host = $config->host;
    
    // retrieve virtual host template
    $vhost = str_replace(
        '$installationPath',
        $installationPath,
        file_get_contents('vassilymas.conf.tpl')
    );
    
    // verify virtualhost directory permissions
    $vhostDir = dirname($vhostLocation);
    if (!is_writable($vhostDir)) {
        throw new Exception("Your user has no permission to write into $vhostDir");
    }
    
    // are dns already loaded? and if not... do I have write permission?
    $hostFileContent = file_get_contents($hostFileLocation);
    if (!strpos($hostFileContent, $host) && !is_writable($hostFileLocation)) {
        throw new Exception("Your user has no permission to write into $hostFileLocation");
    } else {
        // append virtual host dns
        file_put_contents($hostFileLocation, $hostFileContent."\n".$host."\n");
    }
    
    // create virtual host file
    touch($vhostLocation);
    file_put_contents($vhostLocation, $vhost);
    
    // enable apache required modules
    exec("sudo a2ensite vassilymas.conf");
    exec("sudo a2enmod headers");
    exec("sudo a2enmod expires");
    exec("sudo a2enmod deflate");
    
    // restart apache automagicly
    exec("sudo /etc/init.d/apache2 restart");
    
    // creating log folder
    if (!file_exists(SRC_PATH . '/logs')) {
        mkdir(SRC_PATH . '/logs');
        chmod(SRC_PATH . '/logs', 0777);
    }
    // creating php log folder and files
    if (!file_exists(SRC_PATH . '/logs/php')) {
        mkdir(SRC_PATH . '/logs/php');
        chmod(SRC_PATH . '/logs/php', 0777);
    }
    touch(SRC_PATH . '/logs/php/error.log');
    chmod(SRC_PATH . '/logs/php/error.log', 0777);
    // creating apache log folder and files
    if (!file_exists(SRC_PATH . '/logs/apache')) {
        mkdir(SRC_PATH . '/logs/apache');
        chmod(SRC_PATH . '/logs/apache', 0777);
    }
    touch(SRC_PATH . '/logs/apache/error.log');
    chmod(SRC_PATH . '/logs/apache/error.log', 0777);
    touch(SRC_PATH . '/logs/apache/access.log');
    chmod(SRC_PATH . '/logs/apache/access.log', 0777);
    
    // creating cache folder
    if (!file_exists(SRC_PATH . '/application/cache')) {
        mkdir(SRC_PATH . '/application/cache');
    }
    chmod(SRC_PATH . '/application/cache', 0777);
} catch (Exception $e) {
    echo "\n\n\n\n\n\n";
    echo $e->getMessage();
    echo "\n\n\n\n\n\n";
    die;
}