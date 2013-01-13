<?php
class My_Server_VirtualHostConfiguration
{
    const CONFIGURATION_TEMPLATE = <<<CONFIGURATION
<VirtualHost *:80>
   DocumentRoot "%s"
   ServerName %s

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development
    
   <Directory "%s">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>
    
</VirtualHost>
CONFIGURATION;

    private $_sitesEnabledDirectory = null;
    private $_serverName = null;
    private $_documentRoot = null;
    
    public function __construct ($sitesEnabledDirectory, $serverName, $documentRoot)
    {
        $this->setSitesEnabledDirectory($sitesEnabledDirectory);
        $this->setServername($serverName);
        $this->setDocumentRoot($documentRoot);
        $this->_createConfigurationFile();
    }
    
    public function setDocumentRoot ($documentRoot)
    {
        if (!file_exists($documentRoot)) {
            throw new Exception ("Folder $documentRoot does not exists");
        }
        $this->_documentRoot = $documentRoot;
    }
    
    public function getDocumentRoot ()
    {
        return $this->_documentRoot;
    }
    
    public function setServername ($serverName)
    {
        $this->_serverName = $serverName;
    }
    
    public function getServername ()
    {
        return $this->_serverName;
    }
    
    public function setSitesEnabledDirectory ($sitesEnabledDirectory)
    {
        if (!file_exists($sitesEnabledDirectory)) {
            throw new Exception("Folder $sitesEnabledDirectory does not exists");
        }
        $this->_sitesEnabledDirectory = $sitesEnabledDirectory;
    }
    
    public function getSitesEnabledDirectory ()
    {
        return $this->_sitesEnabledDirectory;
    }
    
    public function getRealFilePath()
    {
        return $this->getSitesEnabledDirectory().'/'.$this->getServerName().'.conf';
    }
    
    private function _createConfigurationFile()
    {
        if (!file_exists($this->getRealFilePath())) {
            $file_name = $this->getRealFilePath();
            touch($file_name);
            file_put_contents(
                $file_name,
                sprintf(
                    self::CONFIGURATION_TEMPLATE,
                    $this->getDocumentRoot(),
                    $this->getServerName().'.local',
                    $this->getDocumentRoot()
                )
            );
            chmod($file_name, 0777);
        } else {
            throw new Exception('Cannot create file '.$this->getRealFilePath.', already exists');
        }
    }
    
    public static function isDocumentRootValid($documentRoot)
    {
        return file_exists($documentRoot);
    }
    
    public function removeVirtualHost($virtualHost)
    {
        $domain = substr($virtualHost, 0, -6).'.conf';
        unlink('/etc/apache2/sites-enabled/'.$domain);
    }
}
