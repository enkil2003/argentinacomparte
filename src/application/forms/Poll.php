<?php
class Application_Form_Poll extends Application_Form_AdminAbstract
{
    /**
     * Creates the contact form.
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/forms/poll.ini',
            'poll'
        );
        $this->setConfig($config->poll);
        $this->_populateSelectWithCategories();
    }
    
    public function setPollId($id)
    {
        $this->addElement(
            'hidden',
            'pollId',
            array(
                'value' => $id,
                'decorators' => array(
                    'viewHelper'
                )
            )
        );
        
        $this->populateWithPollId($id);
    }
    protected $_loadedCategories = null;
    private function _populateSelectWithCategories($checked = NULL)
    {
        $select = $this->getElement('category');
        if (NULL === $this->_loadedCategories) {
            $categoryModel = new Category();
            $this->_loadedCategories = $categoryModel->getAll();
        } 
        foreach ($this->_loadedCategories as $category) {
            $select->addMultiOption($category['id'], $category['name']);
        }
        if (NULL !== $checked && in_array($checked, $this->_loadedCategories)) {
            $select->setValue($checked);
        }
        $select->setRequired(true);
    }
    
    private $_loadedPoll = null;
    
    public function populateWithPollId($id) {
        $poll = Doctrine_Core::getTable('Poll')->find($id);
        
        list($year, $month, $day) = explode('-', $poll['creation_date']);
        $date = "{$day}/{$month}/{$year}";
        
        
        $formData['id'] = $id;
        $formData['category'] = $poll['category'];
        $formData['title'] = $poll['title'];
        $formData['questionOne'] = $poll['option1'];
        $formData['questionTwo'] = $poll['option2'];
        $formData['questionThree'] = $poll['option3'];
        $formData['questionFour'] = $poll['option4'];
        $formData['date'] = $date;
        $formData['active'] = $poll['active'];
        
        $this->populate($formData);
    }
}