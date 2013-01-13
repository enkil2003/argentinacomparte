<?php
class Application_Form_DestacadoPortada extends Zend_Form
{
	public function init() 
	{

		$this->removeDecorator('HtmlTag');
		$this->removeDecorator('DtDdWrapper');
		
		$portada = new Zend_Form_Element_Text('id');
		$portada->setLabel('Ingresar código (id) de la política pública o noticia');
		$publicar = new Zend_Form_Element_Submit('publicar');
		$publicar->setAttrib('class', 'btn');
		$this->addElements(array($portada, $publicar));
		$this->addDisplayGroup(
				array('id','publicar'),
				'destacar',
				(array('class'=>'casitabla',"legend" => "Destacar en portada")),
				array("HtmlTag", array("tag" => "span"))
				);
		
		$this->destacar->removeDecorator('DtDdWrapper');
		
	}
}