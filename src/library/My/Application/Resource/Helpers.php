<?php
class My_Application_Resource_Helpers extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @return Zend_Loader_Autoloader_Resource
     */
    public function init()
    {
        return $this->_injectHelpers();
    }

    /**
     * Injects Default Javascript Resources
     * @return Zend_Loader_Autoloader_Resource
     * @FIX autoloaderNamespaces[] = "Vassilymas_"
     *      autoloadernamespaces[] = "My_"
     *      Esto por algun motivo si lo invierto no me anda!.
     *      No probe mucho pero parece que intenta cargar varias veces los mismos resources para todos los plugins 
     *
     */
    protected function _injectHelpers()
    {
        $options = $this->getOptions();
        foreach($options['helpers'] as $option) {
            $class = $options['namespace'].$option;
            Zend_Controller_Action_HelperBroker::addHelper(
                new $class()
            );
        }
        return true;
    }
}

