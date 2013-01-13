<?php
class My_Server_HostsConfiguration
{
    const CONFIGURATION_TEMPLATE = "\n%s\t%s";
    private $_ip = null;
    private $_hosts = null;
    private $_dns = null;
    
    public function setHostFile($hosts)
    {
        $this->_hosts = $hosts;
    }
    
    public function getHostFile()
    {
        return $this->_hosts;
    }
    
    public function setIp($ip)
    {
        if(!$this->isValid($ip)) {
            throw new Exception("$ip is not a valid IP address");
        }
        $this->_ip = $ip;
    }
    
    public function getIp()
    {
        return $this->_ip;
    }

    public function setDns($dns)
    {
        $this->_dns = $dns;
    }
    
    public function getDns()
    {
        return $this->_dns;
    }
    
    private function isValid($ip)
    {
        return Zend_Validate::is($ip, 'Ip');
    }
    
    public function addHost($hosts_file, $ip, $dns)
    {
        $this->setHostFile($hosts_file);
        $this->setIp($ip);
        $this->setDns($dns);
        return $this->add();
    }
    
    private function add()
    {
        if(!$this->hostExists($this->getHostFile(), $this->getDns().'.local')) {
            file_put_contents(
                $this->getHostFile(),
                sprintf(
                    self::CONFIGURATION_TEMPLATE,
                    $this->getIp(),
                    $this->getDns().'.local'
                ),
                FILE_APPEND
            );
            return true;
        } 
    }
    
    public static function hostExists($hostFile, $serverName)
    {
        return (bool)strpos(file_get_contents($hostFile), $serverName);
    }
    
    /**
     * Returns all the .local domains in file
     * @todo Remove hardcoded $hostFile
     * @param array $hostFile .local domains
     */
    public static function getLocalDomains($hostFile = '/etc/hosts')
    {
        $hosts = array();
        $fh = fopen($hostFile, 'r');
        while(!feof($fh)) {
            $line = fgets($fh, 1024);
            if(strpos($line, '.local')) {
                $hosts[trim(substr($line, 10))] = trim(substr($line, 10));
            }
        }
        fclose($fh);
        return $hosts;
    }
    
    public function removeDomain($domain)
    {
        $newHostsFile = '';
        $fh = fopen('/etc/hosts', 'r+');
        while (!feof($fh)) {
            $line = fgets($fh, 1024);
            if (strpos($line, '.local')) {
                $hostName = trim(substr($line, 10));
                if ($hostName ==  $domain) {
                    continue;
                }
            }
            $newHostsFile .= $line;
        }
        fclose($fh);
        touch('/etc/hosts');
        file_put_contents('/etc/hosts', $newHostsFile);
    }
}