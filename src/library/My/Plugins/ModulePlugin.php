<?php
/**
 * Description of Project_Plugins_ModulePlugin
 *
 * @author Jotag
 */
class Project_Plugins_ModulePlugin extends Zend_Controller_Plugin_Abstract {

    	/**
	 * Array of layout paths associating modules with layouts
	 */
	protected $_moduleLayouts;

        /**
	 * Registers a module layout.
	 * This layout will be rendered when the specified module is called.
	 * If there is no layout registered for the current module, the default layout as specified
	 * in Zend_Layout will be rendered
	 *
	 * @param String $module		The name of the module
	 * @param String $layoutPath	The path to the layout
	 * @param String $layout		The name of the layout to render
	 */
	public function registerModuleLayout($module, $layoutPath, $layout=null){
            $this->_moduleLayouts[$module] = array(
                    'layoutPath' => $layoutPath,
                    'layout' => $layout
            );
	}

	public function preDispatch(Zend_Controller_Request_Abstract $request){
            if (is_array($this->_moduleLayouts) && is_string($request->getModuleName()))
            {
                if(isset($this->_moduleLayouts[$request->getModuleName()]))
                {
                        $config = $this->_moduleLayouts[$request->getModuleName()];
                        $layout = Zend_Layout::getMvcInstance();
                        if($layout->getMvcEnabled())
                        {
                            $layout->setLayoutPath($config['layoutPath']);
                            if($config['layout'] !== null){
                               $layout->setLayout($config['layout']);
                            }

                            //Navigation.xml contains the navigation of each module
                            //Grab / Update the nav from config/navigation.xml
                            $filename =  APPLICATION_PATH . '/modules/'.$request->getModuleName() .'/configs/navigation.xml';
                            if(is_file($filename)) {
                                $configNavigation = new Zend_Config_Xml($filename, 'nav');
                                //only if navigation exists
                                $navContainer = new Zend_Navigation($configNavigation);
                                Zend_Registry::set('Zend_Navigation', $navContainer);

                                //Set navigation
                                $view = $layout->getView();
                                $view->navigation($navContainer);
                            }


                            if($request->getModuleName() == 'admin') {
                                $locale = new Zend_Locale('es_ES');
                                Zend_Registry::set('Zend_Locale', $locale);
                            }

                        }
                }
            }
	}

}
?>
