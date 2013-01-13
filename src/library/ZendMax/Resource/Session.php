<?php

namespace Etermax\Resource; 

Class Session extends \Zend_Application_Resource_ResourceAbstract
{
	/**
	 * @return void
	 */
	public function init()
	{
		$options = $this->getOptions();
		\Zend_Session::start($options);
	}
}
