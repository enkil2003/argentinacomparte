<?php

namespace Etermax\Application\Form ;

use \Etermax\Form\Element as Element;

/**
 * This class overide Zend_Form. We have some extra functionality like
 * 
 * pre creted element, some basic element, only need call a method, and this element will add into the form
 * 
 * _setViewScriptPath, is more intuitive to use, only need a view path
 * And other Stuff
 * @author pablo
 *
 */
class Form extends \Zend_Form
{
    public function __construct($option = null ) {
        $this->addPrefixPath('Etermax_Form_Element', 'Etermax/Form/Element/', 'element');
        return parent::__construct($option);
        
            //->addElement('location');
        
    }
    
    /**
     * Remove all decorators from the form
     */
    public function removeAllDecorator ()
    {
        foreach ($this->getElements() as $e) {
            $e->removeDecorator('HtmlTag');
            $e->removeDecorator('Label');
            $e->removeDecorator('Description');
            $e->removeDecorator('DtDdWrapper');
            $e->removeDecorator('Tooltip');
            $e->removeDecorator('Errors');
        }
    }

    /**
     * If some element is invalid, we add a class error to element
     * @see Zend_Form::isValid()
     */
    public function isValid ($data)
    {
        $valid = parent::isValid( $data );
        $this->_checkEtermaxElements($data);        
        if( $valid ) {
            return true;
        }
        
        foreach( $this->getErrors() as $element => $error  ) {
            if( count( $error ) == 0 ) {
                continue;
            }
            $class = $this->$element->getAttrib('class') . ' error';
            $this->$element->setAttrib('class', $class);
            /*var_dump( $element );
            var_dump($error);exit;*/
        }
    }
    
    protected function _setViewScript ($scriptPath)
    {
        $this->setDecorators(
            array(
                array('ViewScript', array('viewScript' => $scriptPath ) ) ));
    }

    /**
     * TODO 
     * 		- think in subforms
     * 		- showCustom errors, hide some messages
     * 		- how to integrate with isvalid
     */
    public function showMessages()
    {
    	$elementsMsgs = $this->getMessages();
    	if(count($elementsMsgs) == 0){
    		return '';
    	}
    	$html = '<div class="alert-message block-message error">';
		foreach($elementsMsgs as $name => $msgs){
			$html .= "<p><strong>" . $this->$name->getLabel() . "</strong>: ";
			reset($msgs);
			$html .= "<em>" . current($msgs). "</em></p>";
		}
		$html .= '</div>';
		return $html;
    }
    
    protected function _getLongText ($name)
    {

        $this->addElement('textarea', $name, 
            array('class' => 'xxlarge', 'rows' => '2', 
                'cols' => '3', 'required' => true, 
                'filters' => array('StringTrim' ), 
                'validators' => array()
            )
        );
    }

    protected function _getSmallText ($name = 'title', $placeHolder = '')
    {

        $this->addElement('text', $name, 
            array('class' => 'mini', 'size' => 8, 'maxlength' => 10, 
                'placeholder' => $placeHolder, 'required' => false, 
                'filters' => array('StringTrim' ), 
                'validators' => array(
                    array('StringLength', true, array(1, 10 ) ) ) ));
    }

    protected function _getTinyText ($name = 'title', $placeHolder = '')
    {

        $this->addElement('text', $name, 
            array('class' => 'medium', 'size' => 20, 'maxlength' => 20, 
                'placeholder' => $placeHolder,
                'required' => false,
                'filters' => array('StringTrim' ), 
                'validators' => array(
                    array('StringLength', true, array(4, 20 ) ) ) ));
    }

    protected function _getMediumText ($name = 'title', $placeHolder = '')
    {

        $this->addElement('text', $name, 
            array('class' => 'medium', 'size' => 50, 'maxlength' => 50, 
                'placeholder' => $placeHolder, 'required' => false, 
                'filters' => array('StringTrim' ), 
                'validators' => array(
                    array('StringLength', true, array(4, 50 ) ) ) ));
    }

    protected function _getText ($name = 'title', $placeHolder = '')
    {

        $this->addElement('text', $name, 
            array('class' => 'xlarge', 'size' => 150, 'maxlength' => 150, 
                'placeholder' => $placeHolder, 'required' => false, 
                'filters' => array('StringTrim' ), 
                'validators' => array(
                    array('StringLength', true, array(5, 150 ) ) ) ));
    }

    protected function _getEmail ()
    {

        $this->addElement('text', 'email', 
            array('class' => 'xlarge', 'size' => 150, 'maxlength' => 150, 
                'required' => true,
                'placeholder' => 'usuario@dominio.com', 
                'filters' => array('StringTrim' ), 
                'validators' => array(
                    array('StringLength', true, array(5, 150 ) ), 'EmailAddress' ) ));
    
    }

    protected function _getUsername ()
    {

        $this->addElement('text', 'username', 
            array('label' => 'Username', 'class' => 'xlarge disabled', 
                'size' => 50, 'disabled' => 'disabled', 'maxlength' => 50, 
                'required' => true, 
            	'placeholder' => 'username',
                'filters' => array('StringTrim' ), 
                'validators' => array(array('StringLength', true, array(5, 50 ) ), 
                    'Alnum' ) ));
    }

    protected function _getPassword ()
    {

        return $this->addElement('password', 'password',
            array('class' => 'xlarge', 'size' => 50, 'maxlength' => 50, 
                'required' => true,
            	'placeholder' => 'password',
                'filters' => array('StringTrim', 'HtmlEntities' ), 
                'validators' => array(array('StringLength', true, array(6, 50 ) ) ) ));
    }
    
    protected function _getPasswordConfirm ()
    {
        return $this->addElement('password', 'password_confirm', 
            array('class' => 'xlarge', 'size' => 50, 'maxlength' => 50, 
                'required' => true, 
            	'placeholder' => 'password',
                'filters' => array('StringTrim', 'HtmlEntities' ), 
                'validators' => array(array('identical', false, array('token' => 'password') ) ) ));
    }

    protected function _getUsernameMail ()
    {

        return $this->addElement('text', 'username', 
            array('class' => 'xlarge', 'size' => 150, 'maxlength' => 150, 
                'required' => true, 
                'filters' => array('StringTrim' ), 
                'validators' => array(
                    array('StringLength', true, array(5, 150 ) ) ) ));
    
    }

    protected function _getTimezone ()
    {

        $this->addElement('select', 'timezone_id', 
            array('class' => 'medium', 'required' => false, 
                'filters' => array('StringTrim' ) ));
        
        $em = \Zend_Registry::get('doctrine');
        
        $repository = $em->getRepository('Model\Timezones');
        
        $timezones = $repository->getAsKeyValue();
        
        $this->timezone_id->setMultiOptions($timezones);
    
    }

    protected function _getLanguage ()
    {

        $this->addElement('select', 'language_id', 
            array('class' => 'medium', 'required' => false, 
                'filters' => array('StringTrim' ) ));
        
        $em = \Zend_Registry::get('doctrine');
        
        $repository = $em->getRepository('Model\LanguageCountries');
        
        $languages = $repository->getAsKeyValue();
        
        $this->language_id->setMultiOptions($languages);
    
    }

    protected function _getLocationElements ()
    {
                
        $this->addElement('select', 'country_id', 
            array('class' => 'medium', 'required' => false, 
                'RegisterInArrayValidator' => false, 
                'filters' => array('StringTrim' ) ));
        
        $em = \Zend_Registry::get('doctrine');
        
        $repository = $em->getRepository('Model\Zones');
        
        $languages = $repository->getAsKeyValue(0);
        
        $this->country_id->addMultiOption('', 'Country');
        $this->country_id->addMultiOptions($languages);
        
        $this->addElement('select', 'province_id', 
            array('class' => 'medium', 'placeholder' => 'State', 
                'RegisterInArrayValidator' => false, 'required' => false, 
                'filters' => array('StringTrim' ) ));
        
        $this->province_id->addMultiOption('', 'Select Country');
        
        $this->addElement('select', 'city_id', 
            array('class' => 'medium', 'placeholder' => 'City', 
                'RegisterInArrayValidator' => false, 'required' => false, 
                'filters' => array('StringTrim' ) ));
        
        $this->city_id->addMultiOption('', 'Select State');
    	
    }

    private function _setLocationValues( $cityId ) 
    {

        if( !isset ($this->city_id )) {
            return ;
        }
        $em = \Zend_Registry::get('doctrine');
        $repository = $em->getRepository('Model\Zones');
        
        $parents = $repository->getParents($cityId);
        if( count( $parents )){
            $cities = $repository->getAsKeyValue($parents[0]['parent_id']);
            $this->city_id->addMultiOptions( $cities );
            $this->city_id->setValue($parents[0]['id']);
                
            $cities = $repository->getAsKeyValue($parents[1]['parent_id']);
            $this->province_id->addMultiOptions( $cities );
            $this->province_id->setValue($parents[1]['id']);
            
            $this->country_id->setValue($parents[2]['id']);
        }
        
    }
    
    private function _checkEtermaxElements($data)
    {
        foreach( $data as $element  => $value) {
            if( $element == 'city_id' ){
                $this->_setLocationValues( $value );
            }
        }
    }
    
    public function populate( $data )
    {
        $val = parent::populate($data );
        $this->_checkEtermaxElements($data);
        return $val;
    }
}
