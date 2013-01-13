<?php
/**
 * Uses the Phing Task
 */
require_once 'phing/Task.php';

/**
 * Task lint css
 * 
 * This file was created based on KpMinTasks, I havent checked if everything works fine,
 * but it does the work.
 * 
 * @tutorial https://github.com/stubbornella/csslint/wiki/Command-line-interface
 * @author Ricardo Buquet
 */
class CssLintTask extends Task {
    /**
     * path to Rhino Js
     *
     * @var  string
     */
    protected $rhinoPath;
    
    /**
     * path to Rhino Js
     *
     * @var  string
     */
    protected $format = 'compact';
    
    /**
     * path to CssLint Js
     *
     * @var  string
     */
    protected $cssLintPath;
    
    /**
     * the source files
     *
     * @var  FileSet
     */
    protected $filesets = array ();
    
    /**
     * Whether the build should fail, if
     * errors occured
     *
     * @var boolean
     */
    protected $failonerror = true;
    
    /**
     * sets the path where Rhino Js can be found
     *
     * @param  string  $rhinoPath
     */
    public function setRhinoPath($rhinoPath) {
        $this->rhinoPath = $rhinoPath;
    }
    
    /**
     * sets the error's output format
     *
     * @param  string  $format
     */
    public function setFormat($format) {
        $this->format = $format;
    }
    
    /**
     * sets the path where CssLint can be found
     *
     * @param  string  $cssLintPath
     */
    public function setCssLintPath($cssLintPath) {
        $this->cssLintPath = $cssLintPath;
    }
    
    /**
     * Nested creator, adds a set of files (nested fileset attribute).
     */
    public function createFileSet() {
        $num = array_push ( $this->filesets, new FileSet () );
        return $this->filesets [$num - 1];
    }
    
    /**
     * Whether the build should fail, if an error occured.
     *
     * @param boolean $failonerror
     */
    public function setFailonerror($failonerror) {
        $this->failonerror = $failonerror;
    }
    
    /**
     * The init method: Do init steps.
     */
    public function init() {
        return true;
    }
    
    /**
     * The main entry point method.
     */
    public function main() {
        $command = 'java -jar {rhinoPath} {cssLintPath} --format={format} {file}' . PHP_EOL;
        
        foreach ( $this->filesets as $fs ) {
            try {
                $files = $fs->getDirectoryScanner ( $this->project )->getIncludedFiles ();
                $fullPath = realpath ( $fs->getDir ( $this->project ) );
                
                foreach ( $files as $file ) {
                    $this->log ( 'CssLinting file ' . $file );
                    
                    $cmd = str_replace ( '{rhinoPath}', realpath ( $this->rhinoPath ), $command );
                    $cmd = str_replace ( '{cssLintPath}', realpath ( $this->cssLintPath ), $cmd );
                    
                    $cmd = str_replace ( '{format}', $this->format, $cmd );
                    $cmd = str_replace ( '{options}', $this->options, $cmd );
                    
                    $cmd = str_replace ( '{file}', realpath ( $fullPath . '/'. $file ), $cmd );
                    
                    $output = array ();
                    $return = null;
                    
                    exec ( $cmd, $output, $return );
                    $errorMessage = 'The following errors were found:' . PHP_EOL . PHP_EOL;
                    foreach ( $output as $line ) {
                        if (strpos($line, 'problem') || strpos($line, 'error')) {
                            $return = 1;
                        }
                        $errorMessage .= $line.PHP_EOL;
                        $this->log ( $line, Project::MSG_VERBOSE );
                    }
                    $errorMessage .= PHP_EOL;
                    if ($return == 1) {
                        echo $errorMessage;
                    }
                    if ($return != 0) {
                        echo $errorMessge;
                        throw new BuildException ( "Task exited with code $return" );
                    }
                
                }
            } 

            catch ( BuildException $be ) {
                // directory doesn't exist or is not readable
                if ($this->failonerror) {
                    throw $be;
                } else {
                    $this->log ( $be->getMessage (), $this->quiet ? Project::MSG_VERBOSE : Project::MSG_WARN );
                }
            }
        }
    }
}
