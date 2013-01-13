<?php
/**
 * Admin Controller
 * @author ricardo
 *
 */
class AdminController extends Zend_Controller_Action
{
    const POLITICA_PUBLICA = 'politicaPublica';
    const NOTICIA = 'noticia';
    const TEMA = 'tema';
    const TRAMITE = 'tramite';
    const BANNER = 'banner';
    const FAQ = 'faq';
    const PREDETERMINADO = 'predeterminado';
    const ENCUESTAS = 'encuestas';
    const GEOLOC = 'geoloc';
    
    private $_bannerDir = null;
    
    public function addPoliticasPublicasStepOneAction()
    {
        $this->_request->setParam('id', $this->_addOrEditPublicPolitics());
        $this->_forward('add-politicas-publicas-step-two');
    }
    
    public function addPoliticasPublicasStepTwoAction()
    {
        $this->view->folder = $this->_request->getParam('id');
        $this->view->active = self::POLITICA_PUBLICA;
        $this->_loadPlupload();
        $this->view->politicaPublicaForm = new Application_Form_PoliticaPublicaStepTwo();
        $this->view->footerScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->footerScript()->appendFile("/js/modules/admin/step2.js");
    }
    
    public function uploadPoliticasPublicasImagesAction()
    {
    	$folder = $this->_request->getParam('folder');
    	$this->_uploadImage($folder);
    }
    
    public function init()
    {
        $this->view->layout()->setLayout('admin');
        $this->view->headTitle('Backend');
        
        $this->view->headLink()->setContainer(
            new Zend_View_Helper_Placeholder_Container()
        );
        
        $this->view->headLink()->setStylesheet('/css/lib/bootstrap/bootstrap.css');
        $this->view->headLink()->appendStylesheet('/css/smoothness/jquery-ui-1.8.15.custom.css');
        $this->view->headLink()->appendStylesheet('/css/admin.css');

        $this->view->headScript()->appendFile('/js/lib/bootstrap/bootstrap-dropdown.js');
        $this->view->headScript()->appendFile('/js/modules/admin/default.js');
        $this->view->headScript()->appendFile('/js/jquery/plugin/jquery.plugin.scrollTo.min.js');
        
        $this->_bannerDir = APPLICATION_TMP_DIR . '/../banners/';
        if (!is_writable($this->_bannerDir)) {
            throw new Exception("El directorio {$this->_bannerDir} no tiene permisos de escritura");
        }
    }
    
    public function indexAction()
    {
        $this->view->headScript()->appendFile('/js/lib/bootstrap/bootstrap-alert.js');
        $this->view->poll1 = Poll::getByCategory('1');
        $this->view->poll2 = Poll::getByCategory('2');
        $this->view->poll3 = Poll::getByCategory('3');
        $this->view->poll4 = Poll::getByCategory('4');
        $this->view->poll5 = Poll::getByCategory('5');
        $this->view->poll6 = Poll::getByCategory('6');
    }
    
    public function agregarPoliticaPublicaAction()
    {
        // se esta agregando un index.js desde el ini del form
        $this->view->active = self::POLITICA_PUBLICA;
        $this->_loadPlupload()->_loadTinyMce()->_loadJavascriptTextLimit();
        $this->view->politicaPublicaForm = new Application_Form_PoliticaPublicaStepOne();
        $this->view->footerScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->footerScript()->appendFile("/js/modules/admin/tinyMCEConfig.js");
        $this->view->footerScript()->appendFile("/js/modules/admin/datepickerConfig.js");
        $this->view->footerScript()->appendFile("/js/modules/admin/addPublicPolitics.js");
    }
    
    public function editarPoliticaPublicaAction()
    {
        $this->view->active = self::POLITICA_PUBLICA;
        $request = $this->getRequest();
        if (NULL !== ($id = $request->getParam('id', null))) {
            $form = new Application_Form_PoliticaPublica();
            $form->getElement('publicPoliticsSubmit')->setLabel('Modificar');
            $form->setPublicPoliticsId($id);
            $this->_loadPlupload()->_loadTinyMce()->_loadJavascriptTextLimit();
            $publicPoliticsModel = new News();
            $publicPolitic = $publicPoliticsModel->findById($id, false);
            $form->populateWithPublicPoliticId($publicPolitic);
            $this->view->form = $form->render();
        }
        $this->view->geolocs = $publicPolitic['Geolocalization'];
        $this->view->id = $id;
        $this->view->headScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->headScript()->appendFile("/js/modules/admin/tinyMCEConfig.js");
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
        $this->view->headScript()->appendFile('/js/modules/admin/selectPublicPolitics.js');
        $this->view->headScript()->appendFile('/js/modules/admin/addPublicPolitics.js');
    }
    
    public function eliminarPoliticaPublicaAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_DeletePoliticPublics();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            News::deleteById($request->getPost('publicPolitic'));
            $this->_redirect('/admin/eliminar-politica-publica');
        }
        $this->view->form = $form;
        $this->view->headScript()->appendFile('/js/modules/admin/selectPublicPolitics.js');
        $this->view->headScript()->appendFile('/js/modules/admin/deletePublicPolitics.js');
    }

    public function aPapeleraNewsAction() {
        $request = $this->getRequest();
        if ($request->getPost('id')  != 'null') {
            News::TrashById($request->getParam('id'));
        }
        $this->_helper->redirector->gotoSimple($request->getParam('volver'),'admin' );
        //$this->getHelper('viewRenderer')->setNoRender();
    }
    
    public function agregarNoticiaAction()
    {
        // se esta agregando un news.js desde el ini del form
        $this->view->active = self::NOTICIA;
        $this->_loadPlupload()->_loadTinyMce()->_loadJavascriptTextLimit();
        $this->view->form = new Application_Form_News();
        $this->view->headScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->headScript()->appendFile("/js/modules/admin/tinyMCEConfig.js");
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
        $this->view->lateScript()->appendFile('/js/modules/admin/news.js');
    }
    
    public function editarNoticiaAction()
    {
        $this->view->active = self::NOTICIA;
        $request = $this->getRequest();
        if (NULL !== ($id = $request->getParam('id', null))) {
            $form = new Application_Form_News();
            $form->getElement('newsSubmit')->setLabel('Modificar');
            $form->setNewsId($id);
            $this->_loadPlupload()->_loadTinyMce()->_loadJavascriptTextLimit();
            $form->populateWithNewsId($id);
            $this->view->form = $form->render();
        }
        $this->view->id = $id;
        $this->view->headScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->headScript()->appendFile("/js/modules/admin/tinyMCEConfig.js");
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
        $this->view->headScript()->appendFile('/js/modules/admin/selectPublicPolitics.js');
        $this->view->headScript()->appendFile('/js/modules/admin/news.js');
    }
    
    public function editarFaqAction()
    {
        $request = $this->getRequest();
        $id = (int)$request->getParam('id');
        $this->view->active = self::FAQ;
        $this->_loadTinyMce();
        $form = new Application_Form_Faq();
        $form->setFaqId($id);
        $form->populateWithFaqId($id);
        $form->getElement('faqSubmit')->setLabel('Modificar');
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $faq = Doctrine_Core::getTable('Faq')->find($id);
            $faq->title = $form->getValue('title');
            $faq->copy = $form->getValue('copy');
            $faq->body = $form->getValue('body');
            list($day, $month, $year) = explode('/', $form->date->getValue());
            $faq->creation_date = "{$year}-{$month}-{$day}";
            $faq->news_id = $form->getValue('pp');
            $faq->active = $form->getValue('active');
            $faq->save();
            $this->_redirect('/admin/listar-faq');
        }
        
        $this->view->form = $form;
        $this->view->headScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->headScript()->appendFile("/js/modules/admin/tinyMCEConfig.js");
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
    }
    
    public function testAction()
    {
        $form = new Application_Form_Test();
        $this->_loadPlupload()->_loadTinyMce()->_loadJavascriptTextLimit();
        $this->view->form = $form;
    }
    
    private function _addOrEditPublicPolitics()
    {
        $request = $this->getRequest();
        $form = new Application_Form_PoliticaPublica();
        $form->populate($request->getPost());
        $politicaPublicaSession = new Zend_Session_Namespace('folderSession');
        if ($request->getPost('id') != 'null' && $request->getPost('id') != null) {
            $politicaPublicaSession->edit = true;
            $news = Doctrine_Core::getTable('News')->find($request->getPost('id'));
            // si estoy editando deberia eliminar primero la categoria a la cual corresponde y reasignar
            $q = new Doctrine_Query();
            $q->delete('NewsHasCategory nhc')
                ->where('nhc.news_id = '.$request->getPost('id'));
            $q->execute();
        } else {
            $news = new News();
        }
        $news->title = $form->title->getValue();
        $news->copy = $form->copy->getValue();
        $news->body = $form->body->getValue();
        $news->user = 1;
        $news->youtube = $form->youtube->getValue();
        $news->preferential_category = $form->preferentialCategory->getValue()
            ? $form->preferentialCategory->getValue()
            : NULL;
        list($day, $month, $year) = explode('/', $form->date->getValue());
        $news->creation_date = "{$year}-{$month}-{$day}";
        $news->active = $form->active->getValue();
        $news->save();
        
        foreach ($form->category->getValue() as $newsHasCategoryValue) {
            $newHasCategory = new NewsHasCategory();
            $newHasCategory->news_id = $news->id;
            $newHasCategory->category_id = $newsHasCategoryValue;
            $newHasCategory->save();
        }
        $politicaPublicaSession->folder = $news->id;
        return $news->id;
    }
    
    public function listarPoliticasPublicasAction() {
        $this->view->headScript()->appendFile("/js/data_tables/js/jquery.dataTables.custom.pp.js");
        $request = $this->getRequest();
        $this->view->active = self::POLITICA_PUBLICA;
        $this->_loadDataTables();
        $politicas = new News();
        $this->view->politicas = $politicas->listPublicPolitics();
        $this->view->controller = "hola";
    }
    
    private function _addOrEditNews()
    {
        $request = $this->getRequest();
        $form = new Application_Form_News();
        $form->populate($request->getPost());
        
        $politicaPublicaSession = new Zend_Session_Namespace('folderSession');
        if ($request->getPost('id')  != 'null') {
            $politicaPublicaSession->edit = true;
            $news = Doctrine_Core::getTable('News')->find($request->getPost('id'));
        } else {
            $news = new News();
        }
        $news->title = $form->title->getValue();
        $news->copy = $form->copy->getValue();
        $news->mintit = $form->mintit->getValue();
        $news->body = $form->body->getValue();
        $news->user = 1;
        $news->youtube = $form->youtube->getValue();
        $news->news_id = $form->pp->getValue();
        $news->preferential_category = $form->preferentialCategory->getValue()
            ? $form->preferentialCategory->getValue()
            : NULL;
        list($day, $month, $year) = explode('/', $form->date->getValue());
        $news->creation_date = "{$year}-{$month}-{$day}";
        $news->active = $form->active->getValue();
        $news->save();
        
        $folderSession = new Zend_Session_Namespace('folderSession');
        $folderSession->folder = $news->id;
        return $news->id;
    }
    
    public function listarNoticiasAction() {

        $this->view->headScript()->appendFile("/js/data_tables/js/jquery.dataTables.custom.news.js");
        
        $request = $this->getRequest();
        $this->view->active = self::NOTICIA;
        $this->_loadDataTables();
        $noticias = new News();
    
        $this->view->noticias = $noticias->listNews();
    }
    
    public function listarEncuestasAction() {
        $this->view->headScript()->appendFile("/js/data_tables/js/jquery.dataTables.custom.poll.js");
        $this->view->headScript()->appendFile('/js/modules/admin/poll-results.js');
        
        $request = $this->getRequest();
        $this->view->active = self::ENCUESTAS;
        $this->_loadDataTables();
        $poll = new Poll();
        $this->view->encuestas = $poll->fetchAll();
    }
    
    public function agregarTramiteAction()
    {
        $request = $this->getRequest();
        $this->view->active = self::TRAMITE;
        $this->_loadTinyMce();
        $form = new Application_Form_Tramite();
        
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $tramite = new Tramite();
            $tramite->title = $form->getValue('title');
            $tramite->body = $form->getValue('body');
            $tramite->youtube = $form->getValue('youtube');
            list($day, $month, $year) = explode('/', $form->date->getValue());
            $tramite->creation_date = "{$year}-{$month}-{$day}";
            $tramite->active = $form->getValue('active');
            $tramite->save();
            $form->reset();
        }
        
        $this->view->form = $form;
        $this->view->headScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->headScript()->appendFile("/js/modules/admin/tinyMCEConfig.js");
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
    }
    
    public function editarTramiteAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        $this->view->active = self::TRAMITE;
        $this->_loadTinyMce();
        $form = new Application_Form_Tramite();
        $form->setTramiteId($id);
        $form->populateWithTramiteId($id);
        $tramite = Doctrine_Core::getTable('Tramite')->find($id);
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $tramite->title = $form->getValue('title');
            $tramite->body = $form->getValue('body');
            $tramite->youtube = $form->getValue('youtube');
            list($day, $month, $year) = explode('/', $form->date->getValue());
            $tramite->creation_date = "{$year}-{$month}-{$day}";
            $tramite->active = $form->getValue('active');
            $tramite->save();
            $this->_redirect('/admin/listar-tramites');
        }
        $this->view->id = $id;
        $this->view->form = $form;
        $this->view->headScript()->appendFile("/js/modules/admin/cancelSubmitWithEnterKey.js");
        $this->view->headScript()->appendFile("/js/modules/admin/tinyMCEConfig.js");
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
    }
    
    public function listarTramitesAction()
    {
        $this->view->headScript()->appendFile("/js/data_tables/js/jquery.dataTables.custom.js");

        $request = $this->getRequest();
        $this->view->active = self::TRAMITE;
        $this->_loadDataTables();
        $tramites = new Tramite();
        
        $this->view->tramites = $tramites->listarTramites();
    }
    
    public function aPapeleraTramiteAction() {
        $request = $this->getRequest();
        if ($request->getPost('id')  != 'null') {
            Tramite::TrashById($request->getParam('id'));
        }
        $this->_helper->redirector->gotoSimple($request->getParam('volver'),'admin' );
        //$this->getHelper('viewRenderer')->setNoRender();
    }
    
    public function agregarBannerAction()
    {
        $request = $this->getRequest();
        $this->view->active = self::BANNER;
        $form = new Application_Form_Banner();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $banner = new Banners();
            $banner->image = $this->_recibirBanner();
            $banner->title = $form->getElement('title')->getValue();
            $banner->position = (int)$form->getElement('position')->getValue();
            $banner->href = $form->getElement('href')->getValue();
            $banner->active = (int)$form->getElement('active')->getValue();
            $banner->save();
            $form->reset();
        }
        
        $this->view->form = $form;
    }
    
    private function _eliminarBanner($banner)
    {
        unlink($this->_bannerDir . $banner->image);
    }
    
    private function _recibirBanner($crop = false)
    {
        $dir = $this->_bannerDir;
        do {
            $destination = time().$_FILES['banner']['name'];
        } while (file_exists($dir . $destination));
        
        move_uploaded_file($_FILES['banner']['tmp_name'], $dir . $destination);
        if ($crop) {
            $this->_shrinkImage($dir . $destination, null, 140, 120, 100);
        }
        return $destination;
    }
    
    public function listarBannersAction()
    {
        $this->view->headScript()->appendFile("/js/data_tables/js/jquery.dataTables.custom.js");
        $this->view->headScript()->appendFile('/js/modules/admin/view-image-modal.js');
        $request = $this->getRequest();
        $this->view->active = self::BANNER;
        $this->_loadDataTables();
        $banners = new Banners();
    
        $this->view->banners = $banners->listBanners();
    }
    
    public function aPapeleraBannerAction() {
        $request = $this->getRequest();
        if ($request->getPost('id')  != 'null') {
            Banners::TrashById($request->getParam('id'));
        }
        $this->_helper->redirector->gotoSimple($request->getParam('volver'),'admin' );
        //$this->getHelper('viewRenderer')->setNoRender();
    }
    
    public function editarBannerAction()
    {
        $request = $this->getRequest();
        $id = (int)$request->getParam('id');
        $this->view->active = self::BANNER;
        $form = new Application_Form_Banner();
        $form->setBannerId($id);
        $form->populateWithBannerId($id);
        // como estoy editando puede no venir ninguna imagen, elimino el validador de cantidad de imagen
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $bannerElement = $form->getElement('banner');
            $banner = Doctrine_Core::getTable('Banners')->find($id);
            $image = $banner->image;
            if ($_FILES['banner']['size'] > 0) {
                $newBanner= $this->_recibirBanner();
                $this->_eliminarBanner($banner->image);
                $banner->image = $newBanner;
            }
            $banner->title = $form->getElement('title')->getValue();
            $banner->position = (int)$form->getElement('position')->getValue();
            $banner->href = $form->getElement('href')->getValue();
            $banner->active = (int)$form->getElement('active')->getValue();
            $banner->save();
            //$form->reset();
            $this->_redirect('/admin/listar-banners');
        }
        $loadedBanner = $form->getLoadedBanner();
        $this->view->banner = $loadedBanner['image'];
        $this->view->form = $form;
    }
    
    public function predeterminarPortadaAction()
    {
        $request = $this->getRequest();
        
        $this->view->active = self::PREDETERMINADO;
        
        $form = new Application_Form_DestacadoPortada();
        
        if ($request->isPost() && $form->isValid($request->getPost())) {
            Predeterminar::publicarPortada($form->id->getValue());
        } 
        
        $this->view->form = $form;
        
        $portada = Predeterminar::findPortada();
        $destacar = News::findById($portada['value']) ;
        $this->view->actualDestaque = $destacar['title'];

        $this->view->headScript()->appendFile('/js/lib/bootstrap/bootstrap-alert.js');
    }
    
    public function agregarFaqAction()
    {
        // se esta agregando un news.js desde el ini del form
        $request = $this->getRequest();
        $this->view->active = self::FAQ;
        $this->_loadPlupload()->_loadTinyMce()->_loadJavascriptTextLimit();
        $form = new Application_Form_Faq();
        
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $faq = new Faq();
            $faq->title = $form->getValue('title');
            $faq->copy = $form->copy->getValue();
            $faq->body = $form->getValue('body');
//            $faq->user = 1;
            $faq->news_id = $form->pp->getValue();
            $date = explode('/', $form->date->getValue());
            $faq->creation_date = "{$date[2]}-{$date[0]}-{$date[1]}";
            $faq->save();
            $form->reset();
        }
        
        $this->view->form = $form;
        $this->view->headScript()->appendFile('/js/modules/admin/faq.js');
    }
    
    public function listarFaqAction() {
        $this->view->headScript()->appendFile("/js/data_tables/js/jquery.dataTables.custom.js");
    
        $request = $this->getRequest();
        $this->view->active = self::FAQ;
        $this->_loadDataTables();
        $faq = new Faq();
        $this->view->faq = $faq->getFaq();
    }
    
    public function aPapeleraFaqAction() {
        $request = $this->getRequest();
        if ($request->getPost('id')  != 'null') {
            Faq::TrashById($request->getParam('id'));
        }
        $this->_helper->redirector->gotoSimple($request->getParam('volver'),'admin' );
        //$this->getHelper('viewRenderer')->setNoRender();
    }
    
    private function _loadDataTables()
    {
        $this->view->headScript()->appendFile("/js/data_tables/js/jquery.dataTables.js");
        $this->view->headScript()->appendFile("/js/data_tables/js/dataTables.scroller.js");
        $this->view->headScript()->appendFile("/js/data_tables/js/ColVis.js");
        $this->view->headLink()->appendStylesheet("/js/data_tables/css/ColVis.css");
        $this->view->headLink()->appendStylesheet("/js/data_tables/css/demo_table.css");
        $this->view->headLink()->appendStylesheet("/js/data_tables/css/demo_page.css");
        $this->view->headLink()->appendStylesheet("/js/data_tables/css/dataTables.scroller.css");
        $this->view->headScript()->appendFile('/js/modules/admin/confirm-deletion.js');
        $this->view->headScript()->appendFile('/js/lib/bootstrap/bootstrap-modal.js');
        return $this;
    }
    
    private function _loadTinyMce()
    {
        $this->view->headScript()->appendFile("/js/jquery/plugin/tinymce/tiny_mce.js");
        $this->view->headScript()->appendFile("/js/jquery/plugin/tinymce/jquery.tinymce.js");
        return $this;
    }
    
    private function _loadJavascriptTextLimit()
    {
        $this->view->headScript()->appendFile("/js/jquery/plugin/jquery.limit-1.2.source.js");
        return $this;
    }
    
    private function _loadPlupload()
    {
        $this->view->headLink()->appendStylesheet("/js/jquery/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css");
        $this->view->headScript()->appendFile("/js/yahoo/browserplus-2.4.21.min.js");
        $this->view->headScript()->appendFile("/js/jquery/plupload/js/plupload.full.js");
        $this->view->headScript()->appendFile("/js/jquery/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js");
        return $this;
    }
    
    const JSON_RESPONSE = 0;
    const HTML_RESPONSE = 1;
    const XML_RESPONSE = 2;
    
    public function ajaxAction()
    {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            throw new Exception('only xmlHttpRequests area acepted for this resource');
        }
        $responseType = self::JSON_RESPONSE;
        switch ($request->getParam('do')) {
            case 'addOrEditPublicPolitics':
                $politicaPublicaId = $this->_addOrEditPublicPolitics();
                $json = array('politicaPublicaId' => $politicaPublicaId);
                // null esta llegando como texto ya que viene en los parametros del header
                if ($request->getPost('id') != 'null') {
                    $json['edit'] = true;
                }
                $response = Zend_Json::encode($json);
                break;
            case 'addOrEditNews':
                $noticiaId = $this->_addOrEditNews();
                $json = array('noticiaId' => $noticiaId);
                // null esta llegando como texto ya que viene en los parametros del header
                if ($request->getPost('id') != 'null') {
                    $json['edit'] = true;
                }
                $response = Zend_Json::encode($json);
                break;
            case 'bindImages':
                $folder = new Zend_Session_Namespace('folderSession');
                $binded = $this->_bindImagesAfterInsert($folder->folder);
                $response = Zend_Json::encode(array('binded' => $binded));
                break;
            case 'getPublicPolitics':
                $publicPoliticsModel = new News();
                $publicPolitics = $publicPoliticsModel->getAllNewsFromCategory($request->getParam('id'));
                $response = Zend_Json::encode(array('content' => $publicPolitics));
                break;
            case 'removeImagesFromId':
                $this->_removeImagesFor($request->getPost('id'));
                $response = Zend_Json::encode(array('deleted' => true));
        }
        switch ($responseType) {
            case self::JSON_RESPONSE:
                header('content-type: application/json');
                break;
        }
        $this->view->layout()->disableLayout();
        $this->view->response = $response;
    }
    
    private function _removeImagesFor($id)
    {
        $q = new Doctrine_Query();
            $q->delete('Images i')
                ->where('i.news_id = ?', $id);
            $q->execute();
        
        $dirHandler = opendir(APPLICATION_TMP_DIR . '/' . $id);
        if (!is_resource($dirHandler)) {
            throw new Exception('Directory ' . APPLICATION_TMP_DIR . '/' . $id . 'couldn\'t be opened');
        }
        while (FALSE !== ($file = readdir($dirHandler))) {
            if (!is_dir($file)) {
                @unlink(APPLICATION_TMP_DIR . '/' . $id . '/' . $file);
            }
        }
        closedir($dirHandler);
    }
    
    /**
     * Achica una 'esto es folder'imagen dada, la pisa al guardarla.
     * 
     * @param string $imagePath - ruta a la imagen
     * @param string|null $destination if null same imagePath is used to save the image
     * @param int image width
     * @param int height image height
     * @param int $quality = 75 - calidad a guardar la imagen 
     * @return void
     */
    private function _shrinkImage($imagePath, $destination = null, $width = 380, $height = 222, $quality = 85)
    {
        require_once APPLICATION_PATH . "/../library/PEAR/WideImage/WideImage.php";
        $destination = null == $destination
            ? $imagePath
            : $destination;
        $image = WideImage::load($imagePath);
        $image->resize($width, $height, 'outside', 'any')
            ->crop("left", "top", $width, $height)
            ->saveToFile($destination, $quality);
    }
    
    /**
     * Busca la ultima noticia insertada, toma el id de la noticia de la session y asocia las imagenes
     * @throws Exception Cuando no puede abrir la carpeta donde se encuentran las imagenes
     * 
     * @return boolean
     */
    private function _bindImagesAfterInsert($dirname)
    {
        $dirHandler = opendir(APPLICATION_TMP_DIR . '/' . $dirname);
        if (!is_resource($dirHandler)) {
            throw new Exception('Directory ' . APPLICATION_TMP_DIR . '/' . $dirname . 'couldn\'t be opened');
        }
        $highlight = true;
        while (FALSE !== ($file = readdir($dirHandler))) {
            if (!is_dir($file)) {
                $this->_shrinkImage(APPLICATION_TMP_DIR . '/' . $dirname . '/' . $file);
                $image = new Images();
                $image->name = $file;
                $image->news_id = $dirname;
                $image->highlight = $highlight;
                $image->save();
                $highlight = false;
            }
        }
        closedir($dirHandler);
        return true;
    }
    
    private function _uploadImage($folder)
    {
    	// HTTP headers for no cache etc
    	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    	header("Cache-Control: no-store, no-cache, must-revalidate");
    	header("Cache-Control: post-check=0, pre-check=0", false);
    	header("Pragma: no-cache");
    	
    	// Settings
    	try {
    		if (!is_writable(APPLICATION_TMP_DIR)) {
    			throw new Exception("No se puede escribir en la ruta " . APPLICATION_TMP_DIR);
    		}
    	} catch (Exception $e) {
    		echo $e->getMessage();
    		die;
    	}
    	$targetFolder = APPLICATION_TMP_DIR . '/' . $folder;
    	set_time_limit(0);
    	// Get parameters
    	$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
    	$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
    	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
    	// Clean the fileName for security reasons
    	$fileName = preg_replace('/[^\w\._]+/', '', $fileName);
    	// Make sure the fileName is unique but only if chunking is disabled
    	if ($chunks < 2 && file_exists($targetFolder . DIRECTORY_SEPARATOR . $fileName)) {
    		$ext = strrpos($fileName, '.');
    		$fileName_a = substr($fileName, 0, $ext);
    		$fileName_b = substr($fileName, $ext);
    		$count = 1;
    		while (file_exists($targetFolder . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b)) {
    			$count++;
    		}
    		$fileName = $fileName_a . '_' . $count . $fileName_b;
    	}
    	// Create target dir
    	if (!file_exists($targetFolder)) {
    		@mkdir($targetFolder);
    	}
    	// Look for the content type header
    	if (isset($_SERVER["HTTP_CONTENT_TYPE"])) {
    		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
    	} else {
    		$contentType = $_SERVER["CONTENT_TYPE"];
    	}
    	// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
    	if (strpos($contentType, "multipart") !== false) {
    		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
    			// Open temp file
    			$out = fopen($targetFolder . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
    			if ($out) {
    				// Read binary input stream and append it to temp file
    				$in = fopen($_FILES['file']['tmp_name'], "rb");
    	
    				if ($in) {
    					while ($buff = fread($in, 4096)) {
    						fwrite($out, $buff);
    					}
    				} else {
    					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    				}
    				fclose($in);
    				fclose($out);
    				@unlink($_FILES['file']['tmp_name']);
    			} else {
    				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    			}
    		} else {
    			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
    		}
    	} else {
    		// Open temp file
    		$out = fopen($targetFolder . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
    		if ($out) {
    			// Read binary input stream and append it to temp file
    			$in = fopen("php://input", "rb");
    			if ($in) {
    				while ($buff = fread($in, 4096))
    					fwrite($out, $buff);
    			} else {
    				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    			}
    			fclose($in);
    			fclose($out);
    		} else {
    			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    		}
    	}
    	// Return JSON-RPC response
    	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
    
    /**
     * Default Action for uploading images
     * @return void

     */
    public function uploadImageAction()
    {
    	$folderSession = new Zend_Session_Namespace('folderSession');
    	$this->_uploadImage($folderSession->folder);
    }
    
    public function agregarEncuestaAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Poll();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $pollModel = new Poll();
            $pollModel->category = $form->getElement('category')->getValue();
            $pollModel->title = $form->getElement('title')->getValue();
            $pollModel->option1 = $form->getElement('questionOne')->getValue();
            $pollModel->option2 = $form->getElement('questionTwo')->getValue();
            $pollModel->option3 = $form->getElement('questionThree')->getValue();
            $pollModel->option4 = $form->getElement('questionFour')->getValue();
            list($day, $month, $year) = explode('/', $form->getElement('date')->getValue());
            $pollModel->creation_date = "{$year}-{$month}-{$day}";
            $pollModel->active = $form->getElement('active')->getValue();
            $pollModel->save();
            $form->reset();
        }
        $form->getElement('active')->setValue(1);
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
        $this->view->form = $form;
    }
    
    public function editarEncuestaAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        
        $form = new Application_Form_Poll();
        if ($id) {
            $form->setPollId($id);
            $form->setAction('/admin/editar-encuesta');
        }
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $pollModel = Doctrine_Core::getTable('Poll')->find($request->getPost('pollId'));
            $pollModel->category = $form->getElement('category')->getValue();
            $pollModel->title = $form->getElement('title')->getValue();
            $pollModel->option1 = $form->getElement('questionOne')->getValue();
            $pollModel->option2 = $form->getElement('questionTwo')->getValue();
            $pollModel->option3 = $form->getElement('questionThree')->getValue();
            $pollModel->option4 = $form->getElement('questionFour')->getValue();
            list($day, $month, $year) = explode('/', $form->getElement('date')->getValue());
            $pollModel->creation_date = "{$year}-{$month}-{$day}";
            $pollModel->active = $form->getElement('active')->getValue();
            $pollModel->save();
            $this->_redirect('/admin/listar-encuestas');
        }
        $this->view->headScript()->appendFile("/js/modules/admin/datepickerConfig.js");
        $this->view->form = $form;
    }
    
    public function geolocalizarAction()
    {
        if ($this->_request->isXmlHttpRequest()) {
            $this->view->layout()->disableLayout();
        }
        $this->view->id = (int)$this->_request->getParam('id');
        $this->view->type = $this->_request->getParam('type');
        $this->view->headMeta();
        $options = $this->getFrontController()->getParam('bootstrap')->getApplication()->getOptions();
        $this->view->googleMapApiKey = $options['vendors']['google']['maps']['apiKey'];
    }
    
    public function addGeoLocAction()
    {
        $geoloc = new Geolocalization();
        $geoloc->description = $this->_request->getPost('description');
        $geoloc->lat = $this->_request->getPost('lat');
        $geoloc->lang = $this->_request->getPost('lang');
        $geoloc->active = 1;
        $geoloc->address = $this->_request->getPost('address');
        $id = (int)$this->_request->getPost('id');
        switch($this->_request->getPost('type')) {
            case 'publicPolitic':
            case 'news':
                $geoloc->news = $id;
                break;
            case 'tramite':
                $geoloc->tramite = $id;
                break;
            default:
                throw new Exception("Valores invalidos");
                die;
        }
        $geoloc->save();
    }
}