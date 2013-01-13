<?php
class Ac_Controller_Plugin_Categorize extends Zend_Controller_Plugin_Abstract
{
    const MI_TRABAJO = 1;
    const MI_EDUCACION = 2;
   // const MI_TIEMPO_LIBRE = 3;
    const MI_BIENESTAR = 3;
    const MI_CREATIVIDAD = 4;
    const MI_COMPROMISO = 5;
    const MIS_DERECHOS = 6;
    const VARIOS = 0;
    
    private function _categorize($categoryName, $categoryClass)
    {
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $view->categoryName = $categoryName;
        $view->categoryClass = $categoryClass;
        $view->categoryBackgroundClass = $categoryClass . '-background';
        $view->bodyBackgroundClass = $categoryClass . '-body-background';
//        $view->headScript()->appendFile();
//        $view->headLink()->appendStylesheet(
//            $view->baseUrl() . '/css/categorize/'
//            . $directory
//            . '/categorize.css' 
//        );
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        switch ($request->getParam('id')) {
            case self::MI_TRABAJO:
                $this->_categorize('Mi Trabajo', 'mi-trabajo');
                break;
            case self::MI_EDUCACION:
                $this->_categorize('Mi Educación', 'mi-educacion');
                break;
//            case self::MI_TIEMPO_LIBRE:
//                $this->_categorize('Mi Tiempo Libre','mi-tiempo-libre');
//                break;
            case self::MI_BIENESTAR:
                $this->_categorize('Mi Bienestar', 'mi-bienestar');
                break;
            case self::MI_CREATIVIDAD:
                $this->_categorize('Mi Creatividad', 'mi-creatividad');
                break;
            case self::MI_COMPROMISO:
                $this->_categorize('Mi Compromiso', 'mi-compromiso');
                break;
            case self::MIS_DERECHOS:
                $this->_categorize('Mis Derechos', 'mis-derechos');
                break;
            case self::VARIOS:
                $this->_categorize('', 'varios');
                break;
        }
    }
}