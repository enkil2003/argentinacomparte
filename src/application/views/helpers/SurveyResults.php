<?php
class Application_View_Helper_SurveyResults extends Application_View_Helper_Abstract
{
    protected $_template = 'helpers/surveyResults.phtml';
    
    public function surveyResults($data)
    {
        $this->_data = $data;
        return $this;
    }
}
