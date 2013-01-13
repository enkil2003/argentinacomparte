<?php
class My_Server_PhpIni {
    public $phpIniFile = '/usr/local/zend/etc/php.ini';
    const DEBUGGER_XDEBUG = 'XDebug';
    const DEBUGGER_EXTENSION_XDEBUG = <<<CONFIGURATION
zend_extension=/usr/local/zend/lib/debugger/xdebug.so
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_mode=req
xdebug.remote_host=127.0.0.1
xdebug.remote_port=9000
xdebug.auto_trace=1
xdebug.collect_assignments=1
xdebug.collect_params=2
xdebug.collect_return=1
xdebug.collect_vars=1
CONFIGURATION;
    public $zendDebuggerFile = '/usr/local/zend/etc/conf.d/debugger.ini';
    const DEBUGGER_ZEND = 'Zend';
    const DEBUGGER_EXTENSION_ZENDDEBUGGER = 'zend_extension_manager.dir.debugger="/usr/local/zend/lib/debugger"';
    
    private $_allowedDebuggers = array(self::DEBUGGER_XDEBUG, self::DEBUGGER_ZEND);
    
    public function addExtension()
    {
        
    }
    
    public function switchExtension()
    {
        
    }
    
    public function getExtension($extension)
    {
        
    }
    
    public function setExtesion($extension)
    {
        
    }
    
    public function removeExtension($extension)
    {
        
    }
    
    public function isXDebugEnabled()
    {
        $fh = fopen($this->phpIniFile, 'r');
        while (!feof($fh)) {
            $line = fgets($fh, 1024);
            if(false !== strpos($line, 'xdebug')) {
                return true;
            }
        }
        fclose($fh);
    }
    
    public function enableXDebug()
    {
        if(!$this->isXDebugEnabled()) {
            $fh = fopen($this->phpIniFile, 'a+');
            fputs($fh, self::DEBUGGER_EXTENSION_XDEBUG."\n");
            fclose($fh);
        } else {
            throw new Exception("XDebug already loaded");
        }
    }
    
    public function disableXDebug()
    {
        $newPhpIniFile = '';
        $fh = fopen($this->phpIniFile, 'r');
        while (!feof($fh)) {
            $line = fgets($fh, 1024);
            if(false !== strpos($line, 'xdebug')) {
                continue;
            }
            $newPhpIniFile .= $line;
        }
        fclose($fh);
        file_put_contents($this->phpIniFile, $newPhpIniFile);
    }
    
    public function disableZendDebug()
    {
        $newZendDebuggerFile = '';
        $fh = fopen($this->zendDebuggerFile, 'r');
        while (!feof($fh)) {
            $line = fgets($fh, 1024);
            if(false !== strpos($line, self::DEBUGGER_EXTENSION_ZENDDEBUGGER)) {
                continue;
            }
            $newZendDebuggerFile .= $line;
        }
        fclose($fh);
        file_put_contents($this->zendDebuggerFile, $newZendDebuggerFile);
        
    }
    
    public function isZendDebugEnabled()
    {
        $fh = fopen($this->zendDebuggerFile, 'r');
        while (!feof($fh)) {
            $line = fgets($fh, 1024);
            if(false !== strpos($line, self::DEBUGGER_EXTENSION_ZENDDEBUGGER)) {
                fclose($fh);
                return true;
            }
        }
        fclose($fh);
    }
    
    
    public function setDebugger($debugger = self::DEBUGGER_ZEND)
    {
        if(!in_array($debugger, $this->_allowedDebuggers)) {
            throw new Exception($debugger.' is not an allowed debugger only XDebug and zend debug are allowed');
        }
        switch($debugger) {
            case self::DEBUGGER_XDEBUG:
                if($this->isZendDebugEnabled()) {
                    $this->disableZendDebug();
                }
                $this->enableXDebug();
                break;
            case self::DEBUGGER_ZEND:
                if($this->isXDebugEnabled()) {
                    $this->disableXDebug();
                }
                $this->enableZendDebug();
                break;
                
        }
    }
    
    public function enableZendDebug()
    {
        if(!$this->isZendDebugEnabled()) {
            $fh = fopen($this->zendDebuggerFile, 'a+');
            fputs($fh, self::DEBUGGER_EXTENSION_ZENDDEBUGGER."\n");
            fclose($fh);
        } else {
            throw new Exception("already loaded");
        }
    }
    
    public function disableDebuggers()
    {
        if($this->isZendDebugEnabled()) {
            $this->disableZendDebug();
        }
        if($this->isXDebugEnabled()) {
            $this->disableXDebug();
        }
    }
}