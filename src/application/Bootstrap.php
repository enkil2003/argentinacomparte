<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    const ROUTE_MI_TRABAJO_ID = 1;
    const ROUTE_MI_EDUCACION_ID = 2;
    const ROUTE_MI_BIENESTAR_ID = 3;
    const ROUTE_MI_CREATIVIDAD_ID = 4;
    const ROUTE_MI_COMPROMISO_ID = 5;
    const ROUTE_MIS_DERECHOS_ID = 6;
    
    protected function _initViewCommons()
    {
        $view = $this->bootstrap('view')->getResource('view');
        $this->view->headLink(
            array(
                'rel' => 'shortcut icon',
                'href' => '/img/favicon.ico'
            ),
            'PREPEND'
        );
        $this->view->headTitle()->setSeparator(' - ');
        $view->headTitle('Argentina Comparte');
    }
    
    protected function _initGooglemaps()
    {
        $view = $this->bootstrap('view')->getResource('view');
        $options = $this->getApplication()->getOptions();
        $view->googleMapApiKey = $options['vendors']['google']['maps']['apiKey'];
    }
    
    protected function _initZFDebug()
    {
        if (APPLICATION_ENV !== 'development') {
            return;
        }
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');
        $autoloader->registerNamespace('Danceric');
        
        $this->bootstrap('doctrine');
        
        $options = array(
            'plugins' => array(
                'Variables',
                'Danceric_Controller_Plugin_Debug_Plugin_Doctrine',
                'Exception',
                'html','file'
            )
        );
        
        $debug = new ZFDebug_Controller_Plugin_Debug($options);
        
        $this->bootstrap('frontController');
        $fc = $this->getResource('frontController');
        $fc->registerPlugin($debug);
    }
    /**
     * Configures Zend Router settings.
     * @return Zend_Controller_Router_Interface
     */
    protected function _initRouter()
    {
        $front = Zend_Controller_Front::getInstance();
        
        $front->registerPlugin(new Ac_Controller_Plugin_Categorize());
        
        $router = $front->getRouter();
        $routes = array(
            // Index Controller
            'index' => new Zend_Controller_Router_Route(
                '/', // varios
                array(
                    'controller' => 'category',
                    'action' => 'index',
                    'id' => 0
                )
            ),
            // Category Controller
            'verMas' => new Zend_Controller_Router_Route(
                'ver-mas/id/:id/:slug',
                array(
                    'controller' => 'category',
                    'action' => 'show-detail',
                    'slug' => null
                )
            ),
            'verTramite' => new Zend_Controller_Router_Route(
                'ver-tramite/:tramiteId/:tramiteName',
                array(
                    'controller' => 'category',
                    'action' => 'show-tramite'
                )
            ),
            // Category Controller
            'miTrabajo' => new Zend_Controller_Router_Route(
                'mi-trabajo',
                array(
                    'controller' => 'category',
                    'action' => 'show',
                    'id' => self::ROUTE_MI_TRABAJO_ID
                )
            ),
            'miEducacion' => new Zend_Controller_Router_Route(
                'mi-educacion',
                array(
                    'controller' => 'category',
                    'action' => 'show',
                    'id' => self::ROUTE_MI_EDUCACION_ID
                )
            ),
            'MiBienestar' => new Zend_Controller_Router_Route(
                'mi-bienestar',
                array(
                    'controller' => 'category',
                    'action' => 'show',
                    'id' => self::ROUTE_MI_BIENESTAR_ID
                )
            ),
            'MiCreatividad' => new Zend_Controller_Router_Route(
                'mi-creatividad',
                array(
                    'controller' => 'category',
                    'action' => 'show',
                    'id' => self::ROUTE_MI_CREATIVIDAD_ID
                )
            ),
            'MiCompromiso' => new Zend_Controller_Router_Route(
                'mi-compromiso',
                array(
                    'controller' => 'category',
                    'action' => 'show',
                    'id' => self::ROUTE_MI_COMPROMISO_ID
                )
            ),
            'MisDerechos' => new Zend_Controller_Router_Route(
                'mis-derechos',
                array(
                    'controller' => 'category',
                    'action' => 'show',
                    'id' => self::ROUTE_MIS_DERECHOS_ID
                )
            ),
            // Admin
            'politicasPublicasStepOne' => new Zend_Controller_Router_Route(
                'admin/politicas-publicas-step-one/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'politicas-publicas-step-one',
                    'id' => null
              )
            ),
            'politicasPublicasStepTwo' => new Zend_Controller_Router_Route(
                'admin/politicas-publicas-step-two/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'politicas-publicas-step-two',
                    'id' => null
              )
            ),
            'politicasPublicasStepThree' => new Zend_Controller_Router_Route(
                'admin/politicas-publicas-step-three/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'geolocalizar',
                    'type' => 'publicPolitic'
                )
            ),
            'noticiaStepOne' => new Zend_Controller_Router_Route(
                'admin/noticia-step-one/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'noticia-step-one',
                    'id' => null
                )
            ),
            'noticiaStepTwo' => new Zend_Controller_Router_Route(
                'admin/noticia-step-two/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'noticia-step-two',
                    'id' => null
                )
            ),
            'noticiaStepThree' => new Zend_Controller_Router_Route(
                'admin/noticia-step-three/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'geolocalizar',
                    'type' => 'news'
                )
            ),
            'tramiteStepOne' => new Zend_Controller_Router_Route(
                'admin/tramite-step-one/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'tramite-step-one',
                    'id' => null
              )
            ),
            'tramiteStepTwo' => new Zend_Controller_Router_Route(
                'admin/tramite-step-two/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'geolocalizar',
                    'type' => 'tramite'
                )
            ),
            'predeterminar' => new Zend_Controller_Router_Route(
                'admin/predeterminar/:id',
                array(
                    'controller' => 'admin',
                    'action' => 'predeterminar',
                )
            ),
        );
        $router->addRoutes($routes);
        return $router;
    }
}
