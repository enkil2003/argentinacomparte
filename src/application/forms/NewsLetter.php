<?php
class Application_Form_NewsLetter extends Zend_Form
{
	public function init() 
	{
		$config = new \Zend_Config_Ini(
				APPLICATION_PATH . '/configs/forms/newsletter.ini',
				'newsletter'
		);
		$this->setConfig($config->newsletter);
		
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
	
}