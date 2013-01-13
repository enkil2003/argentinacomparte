<?php
namespace Etermax\Application\Controller;

class Crud extends Secure
{

    /**
     * @var Query for get all rows
     */
    protected $_query;
    /**
     * @var Form's name
     */
    protected $_form;
    /**
     * @var Model's name
     */
    protected $_model;

    /**
     * @var Primary key
     */
    protected $_primary = 'id';
    /**
     * @var IndexAction, Default action when we need redirect some places
     */
    protected $_indexAction;
    /**
     * @var Parent Controller name without modules Prefix
     */
    private $_controller;
    /**
     * @var Object Form
     */
    protected $_formInstance;
    
    /**
     * @var module's name
     */
    private $_module = '';
    
    public function preDispatch()
    {
        parent::preDispatch();
        
        /**
         * Get the Controller name for redirects;
         */
        $this->_setController();
        
        /**
         *  Set the default page for the module
         */
        $this->_setDefaultPage();
        
        if( empty( $this->_form ) ){
            throw new \Exception('You need declare $_form property');
        }
        
        if( empty( $this->_model ) ){
            throw new \Exception('You need declare $_model property');
        }
        
    }
       
    public function createAction()
    {
        $form = new $this->_form();
        
        if( $this->getRequest()->isPost() )  {
        
            if( $form->isValid( $this->_getAllParams() )){
                $entity = new $this->_model();
                
                $entity->setFromArray( $form->getValues() );
                
                /** Hookeable Action  */
                $this->_afterCreate();
                
                $this->_em->persist( $entity );
                $this->_em->flush();
                
                /** Hookeable Action  */
                $this->_beforeCreate();
                return $this->_redirect( $this->_indexAction );
            }
        }

        $this->view->form = $form;
    }
    
    public function updateAction()
    {
        if(!$this->_hasParam('id')){
            return $this->_redirect( $this->_indexAction );
        }
        $form = new $this->_form();
        
        $row = $this->_em->getRepository( $this->_model )
                ->find( $this->_getParam('id') );        
        
        if( $this->getRequest()->isPost() )  {
        
            if( $form->isValid( $this->_getAllParams() )){
                $bind = $form->getValues();
                $bind['id'] = $this->_getParam('id');
                $row->setFromArray( $bind );

                /** Hookeable Action  */
                $this->_afterUpdate();
                $this->_em->persist( $row );
                $this->_em->flush();
                /** Hookeable Action  */
                $this->_beforeUpdate();

                return $this->_redirect( $this->_indexAction );
            }
        }else{
            $form->populate( $row->toArray() );
        }

        $this->view->form = $form;
    }        
    
    public function undoAction()
    {
        if(!$this->_hasParam('id')){
            return $this->_redirect( $this->_indexAction );
        }
        
        
    }
    
    public function statusAction()
    {
        if(!$this->_hasParam('id')){
            return $this->_redirect( $this->_indexAction );
        }
        
        $form = new $this->_form();

        $row = $this->_em->getRepository( $this->_model )
                ->find( $this->_getParam('id') );        
        
        $row->changeStatus();
        
        /** Hookeable Action */
        $this->_afterUpdate();
        $this->_em->persist( $row );
        $this->_em->flush();
        /** Hookeable Action */
        $this->_beforeUpdate();
        
        return $this->_redirect( $this->_indexAction );
    }
    
    public function indexAction()
    {
        $adapter = new \Etermax\Paginator\Adapter\Doctrine( $this->_getQuery() );

        \Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/item.phtml');
        $paginator = new \Zend_Paginator( $adapter );

        if( $this->_hasParam('page')) {
            \Zend_Paginator::setCurrentPageNumber( $this->_getParam('page') );
            $paginator->setCurrentPageNumber($this->_getParam('page'));
        }
        
        $this->view->paginator = $paginator;
    }

    protected function _afterUpdate()
    {
    }
    
    protected function _beforeUpdate()
    {
    }
    
    protected function _afterCreate()
    {
    }
    
    protected function _beforeCreate()
    {
    }
    
    protected function _getQuery()
    {
        throw new \Exception('You need declarate your own _getQuery Method');
    }

    private function _setController()
    {
        $parent = strtolower(
            str_replace( 'Controller', '' ,get_called_class() ) 
        );
        $parentArray = explode( '_', $parent );
        if( count( $parentArray )) {
            $this->_controller = $parentArray[count($parentArray)-1];
            $this->_module = $parentArray[0];
        }else{
            $this->_controller = $parent;
        }
    
    } 
    
    private function _setDefaultPage()
    {
        if( empty( $this->_indexAction) ){
        
            if( !empty( $this->_module )){
                $this->_indexAction = '/' . $this->_module;
            }
            $this->_indexActon .= '/' . $this->_controller . '/index/';
            
        }
    }    
}

