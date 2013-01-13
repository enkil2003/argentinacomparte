<?php
namespace Etermax\Resource;

class HeaderLoader extends \Zend_Application_Resource_ResourceAbstract
{

    public function init ()
    {

        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('layout');
        $layout = $bootstrap->layout;
        $options = $this->getOptions();
        if (isset($options['styles'])) {
            foreach ($options['styles'] as $style) {
                if (empty($style)) {
                    continue;
                }
                $layout->getView()
                    ->headLink()
                    ->appendStylesheet($style);
            }
        }
        
        if (isset($options['scripts'])) {
            
            foreach ($options['scripts'] as $script) {
                
                if (empty($script)) {
                    continue;
                }
                $layout->getView()
                    ->headScript()
                    ->appendFile($script);
            }
        }
        
        if (isset($options['title'])) {
            $layout->getView()->headTitle($options['title']);
        }
        return $layout;
    
    }

}