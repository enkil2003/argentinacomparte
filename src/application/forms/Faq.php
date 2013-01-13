<?php
class Application_Form_Faq extends Application_Form_AdminAbstract
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/faq.ini',
            'faq'
        );
        $this->setConfig($config->faq);

        $this->_populateSelectWithPublicPolitics();
        
    }
    
    private function _populateSelectWithPublicPolitics()
    {
        $publicPoliticsRecords = News::getPublicPolitics();
        $publicPoliticsElement = ($this->getElement('pp'));
        foreach($publicPoliticsRecords as $publicPolitic) {
            $publicPoliticsElement->addMultiOption(
                    $publicPolitic['id'],
                    $publicPolitic['title']
            );
        }
    }
    
    public function setFaqId($id) {
        $this->addElement(
            'hidden',
            'faqId',
            array(
                'value' => $id,
                'decorators' => array(
                    'viewHelper'
                )
            )
        );
    }
    
    private $_loadedFaq = null;
    
    public function populateWithFaqId($id) {
        $faqModel = new Faq();
        $this->_loadedFaq = $faqModel->findById($id);
        
        list($year, $month, $day) = explode('-', $this->_loadedFaq['creation_date']);
        $date = "{$day}/{$month}/{$year}";
        
        $this->getElement('title')->setValue($this->_loadedFaq['title']);
        $this->getElement('copy')->setValue($this->_loadedFaq['copy']);
        $this->getElement('body')->setValue($this->_loadedFaq['body']);
        $this->getElement('date')->setValue($date);
        $this->getElement('active')->setValue($this->_loadedFaq['active']);
        $this->getElement('pp')->setValue($this->_loadedFaq['news_id']);
    }
}