<?php
/*
Ejemplo de uso:
$surveyAdapter = array(
    'title' => $surveyData['title'],
    'options' => array(
        array('option' => $surveyData['option1'], 'score' => $surveyData['optiononevotes']),
        array('option' => $surveyData['option2'], 'score' => $surveyData['optiontwovotes']),
        array('option' => $surveyData['option3'], 'score' => $surveyData['optionthreevotes']),
        array('option' => $surveyData['option4'], 'score' => $surveyData['optionfourvotes']),
    )
);
echo $this->survey($surveyAdapter);
*/

class Application_View_Helper_Survey extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/survey.phtml';
    
    public function survey($data)
    {
        $pollModel = new Poll();
        $data['uniqId'] = uniqid('survey-');
        $data['alreadyVoted'] = Zend_Controller_Front::getInstance()->getRequest()->getCookie("survey_{$data['surveyId']}", false)
            ? true
            : false;
        ;
        $this->_data = $data;
        return $this;
    }
}