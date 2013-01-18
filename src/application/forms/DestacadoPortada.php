<?php
class Application_Form_DestacadoPortada extends Zend_Form
{
    public function init() 
    {
        $portada = new Zend_Form_Element_Text('id');
        $portada->setLabel('Ingresar código (id) de la política pública o noticia');
        $publicar = new Zend_Form_Element_Submit('Publicar');
        $publicar->setAttrib('class', 'btn-primary btn-large');
        $publicar->setOptions(
            array(
                'decorators' => array('viewHelper'),
            )
        );
        $this->addElements(array($portada, $publicar));
    }
}