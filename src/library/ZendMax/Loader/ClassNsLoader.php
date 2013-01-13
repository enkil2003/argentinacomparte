<?php

namespace ZendMax\Loader;

/**
 * Load classes with namespaces
 */
class ClassNsLoader
{
	/**
	 * Extension of the class
	 * @var string
	 */
    protected $fileExtension = '.php';

	/**
	 * Separator of the namespace
	 * @var string
	 */    
	protected $namespaceSeparator = '\\';

	/**
	 * Namespace name
	 * @var string
	 */
	protected $namespace;

	/**
	 * Path of the namespace
	 * @var string
	 */ 		
	protected $includePath;
	
	/**
	 * If convert the namespace to lower case, 
	 * so search in lower case the folder name.
	 * @var	bool
	 */
	protected $folder2LowerCase;
	
	/**
	 * @param	string	$ns
	 * @param	string	$path
	 * @param	bool	$nsToLowerCase
	 */
	public function __construct($ns, $path, $folder2LowerCase = false)
	{
		$this->namespace = $ns;
		$this->includePath = $path;
		$this->folder2LowerCase = $folder2LowerCase;
	}
	
	/**
	 * Load the class with namespace
	 * 
	 * @param	string	$className
	 * @return	bool
	 */
    public function loadClass($className)
    {
    	if ($this->namespace !== null && strpos($className, $this->namespace . $this->namespaceSeparator) !== 0) {
            return false;
        }
        
        //remove the namespace from the front of the className
        $className = str_replace($this->namespace . $this->namespaceSeparator, '', $className);
        
        //put namespaces name to lowercase so match with lowercase directories
        if($this->folder2LowerCase){
	        $parts = explode($this->namespaceSeparator, $className);
	        for($i = 0; $i < count($parts) - 1; $i++){
	        	$parts[$i] = strtolower($parts[$i]);
	        }
	        $className = implode($this->namespaceSeparator, $parts);
        }
        
        $path = $this->includePath . DIRECTORY_SEPARATOR;
        $path .= str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $className);
        $path .= $this->fileExtension;
        
        if(file_exists($path)){
        	return include_once($path);
		} else {
			return false;
		}
    }
}
