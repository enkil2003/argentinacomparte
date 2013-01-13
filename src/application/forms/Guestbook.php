<?php
class Application_Form_Guestbook extends Zend_Form
{
	public function init() 
	{
		$config = new \Zend_Config_Ini(
				APPLICATION_PATH . '/configs/forms/guestbook.ini',
				'guestbook'
		);
		$this->setConfig($config->guestbook);
		
		//$this->view->headScript()->appendFile('/js/modules/default.js');
	}
	

	/**
	 * Prepares base clases to use with twitter's bootstrap css
	 * @return void
	 */
	private function _setClasses()
	{
		foreach($this->getElements() as $element) {
			$decorator = $element->getDecorator('HtmlTag');
			if (!method_exists($decorator, 'setOption')) {
				continue;
			}
			$decorator->setOption('class', 'control-group');
		}
	}
	
	private function _configureJsDecorator()
    {
        $jsvalidation = $this->getDecorator('JsAutoValidation');
        
        $jsvalidation->setOption(
            'validatorOptions', array(
                'onAfterInvalidElement' => new Zend_Json_Expr(
                    <<<onAfterInvalidElement
function(element) {
    element.parent().removeClass('success').addClass('error');
}
onAfterInvalidElement
                ),
                'onAfterValidElement' => new Zend_Json_Expr(
                    <<<onAfterInvalidElement
function(element) {
    element.parent().removeClass('error').addClass('success');
}
onAfterInvalidElement
                )
            )
        );
    }
}