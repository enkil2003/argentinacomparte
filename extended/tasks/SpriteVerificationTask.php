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
class SpriteVerificationTask extends Task {
    /**
     * path to sources images Js
     *
     * @var  string
     */
    protected $sourceImagesPath;
    
    /**
     * path to sprite.less
     *
     * @var  string
     */
    protected $spriteLessPath;
    
    /**
     * Whether the build should fail, if
     * errors occured
     *
     * @var boolean
     */
    protected $failonerror = true;
    
    /**
     * sets the path where to find the source images
     *
     * @param  string  $sourceImagesPath
     */
    public function setSourceImagesPath($sourceImagesPath) {
        $this->sourceImagesPath = $sourceImagesPath;
    }
    
    /**
     * sets the path where to find the sprites.less file
     *
     * @param  string  $format
     */
    public function setSpriteLessPath($spriteLessPath) {
        $this->spriteLessPath = $spriteLessPath;
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
        $handle = @fopen($this->spriteLessPath, "r");
        // gather file lists in source folder
        $dirPointer = opendir($this->sourceImagesPath);
        $allowedExtension = array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'ico');
        $images = array();
        while(($file = readdir($dirPointer)) !== false) {
            if (is_file($this->sourceImagesPath . '/' . $file)) {
                $fileNameParts = explode('.', $file);
                $extension = array_pop($fileNameParts);
                if (in_array($extension, $allowedExtension)) {
                    $images[] = implode('.', $fileNameParts);
                }
            }
        }
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $img = substr(substr($buffer, 0, strpos($buffer, '{')), strlen('.sprite-'));
                if(empty($img)) {
                    continue;
                }
                if (!in_array($img, $images)) {
                    throw new Exception('No se encontro el archivo '.$img);
                }
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        return 1;
    }
}
