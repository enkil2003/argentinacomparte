<?php

class CategoryController extends Zend_Controller_Action
{
    public function init()
    {
//        $facebook = Zend_Registry::get('Facebook');
//        
//        $id_usuario = "argentinacomparte";
//        $token = $facebook->getAccessToken();
//        $respuesta = file_get_contents("https://graph.facebook.com/" . $id_usuario . "/feed?access_token=" . $token);
//        $data = json_decode($respuesta,true);
//        
//        // // $fb = new Facebook_Facebook($facebook['appid'],$facebook['secret']); 
//        // // echo ($fb->getAccessToken());
//        $this->view->headScript()->appendFile('/js/lib/bootstrap/bootstrap-modal.js');
//        $this->view->actionName = $this->getRequest()->getActionName();
//        
//        $this->view->feed = $data;
//        
//        $qbanners = Banners::getBanners();
//        $this->view->banners = $qbanners;
    }
    
    public function showAction()
    {
        $news = new News();
        $id = $this->getRequest()->getParam('id');

        $acordeon = $news->getChildsNewsFromCategory($id, 4);
        //$publicPolitics = $news->getNewsFromCategory($id);
        $publicPolitics = $news->getAllPpFromCategory($id);
        $this->view->acordeon = array();
        $this->view->acordeon['News'] = $acordeon;
        $this->view->publicPolitics = array();
        $this->view->publicPolitics['publicPolitics'] = $publicPolitics;
        if (isset($acordeon[0])) {
            $padre = $acordeon[0]['news_id'];
            if ( NULL === $acordeon[0]['youtube']  || $acordeon[0]['youtube'] == ""){
                $pp = News::getNewsFromId($padre);
                $this->view->codyt = $pp['youtube'];
            } else {
                $this->view->codyt = $acordeon[0]['youtube'];
            }
        }
        $this->view->id = $id;
    }
    
    public function showDetailAction()
    {
        $news = News::findById($this->_request->getParam('id'));
        if (!is_Array($news)) {
            $this->_redirect('/error');
        }
        $this->view->news = $news;
        $news2 = new News();
        $this->view->acordeon = array();
        $this->view->codyt = $news['youtube'];
        
        if ( NULL === $news['preferential_category'] ){
            $padre = $news ['news_id'];
            $pp = News::getNewsFromId($padre) ;
            $categoria = $pp['preferential_category'];
            if ( NULL === $news['youtube'] || $news['youtube'] == ''){
                $this->view->codyt = $pp['youtube'];
            }
            $acordeon = $news2->getChildsNewsFromCategory($categoria,4,$this->_request->getParam('id'));
        }else{
            
            $faq = Faq::findPublicByFather($this->_request->getParam('id'));
            $this->view->faq = $faq;
            $acordeon = $news2->getPublicNewsFromPp($this->_request->getParam('id'));
        }
        
        $this->view->acordeon['News'] = $acordeon;
        
    }
    
    public function indexAction()
    {
        $destacar = News::getHighlight();
        $this->view->codyt = $destacar['youtube'];
        
        if ($destacar['news_id'] != NULL) {  //despejo que es noticia 
             if (NULL === $destacar['youtube'] || $destacar['youtube'] == '') {
                 $padre = $destacar['news_id'];
                 $n = News::getNewsFromId($padre);
                 $this->view->codyt = $n['youtube'];
             }
             $news = News::getLatestHomeNews($destacar['id']); // y no aparecerá en el acordeon
             $publicPolitics = News::getPublicPoliticsRandom(10);
             
        } else { // es política pública y la destacada no aparecerá en el pasador
            $news = News::getLatestHomeNews();
            $publicPolitics = News::getPublicPoliticsRandom(10, $destacar['id']);
        }
        
        $this->view->destacar = $destacar;
        
        $this->view->acordeon = array();
        $this->view->acordeon['News'] = $news;
        
        $this->view->publicPolitics = array();
        $this->view->publicPolitics['publicPolitics'] = $publicPolitics;
        
        $this->renderScript('/category/portada.phtml');
    }
    
    public function showTramiteAction()
    {
        $news = Tramite::findById($this->_request->getParam('tramiteId'));
        
        if (!is_array($news)) {
            $this->_redirect('/error');
        }
        $this->view->news = $news;
        $this->view->codyt = $news['youtube'];
    }
    
    private function _loadFeedEntries($url)
    {
        $cache = $this->_getCacheObj();
        $md5   = md5($url);
        if ($result = $cache->load("feed_{$md5}")) {
            return $result;
        }
        $entries = array();
        try {
            $feed = @Zend_Feed::import($url);
            foreach ($feed as $entry) {
                $entries[] = $entry;
            }
        } catch (Exception $e) {
            // ignore this feed.
        }
    
        $cache->save($entries, "feed_{$md5}");
    
        return $entries;
    }
    
    private function _getCacheObj()
    {
        $frontendOptions = array(
                'lifetime' => self::CACHE_LIFETIME,
                'automatic_serialization' => true
        );
    
        $backendOptions = array(
                'cache_dir' => self::CACHE_DIR
        );
    
        return Zend_Cache::factory(
                'Core',
                'File',
                $frontendOptions,
                $backendOptions
        );
    }
    
}
