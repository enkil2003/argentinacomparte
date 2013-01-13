<?php
/**
 * @author Ricardo
 */
class My_View_Helper_Plupload extends Zend_View_Helper_FormElement
{
    private $_attribs = NULL;
    private $_type = NULL;
    private $_id = NULL;
    
    /**
     * Returns the view helper
     * @TODO: public function formText($name, $value = null, $attribs = null) modificar la interfaz
     */
    public function plupload($type, $id = NULL, $attribs = NULL)
    {
        $this->_id = NULL !== $id
            ? $attribs['id']
            : $type;
        $this->_type = $type;
        $this->_attribs = $attribs;
        return $this;
    }
    
    public function toString()
    {
        $classes =  isset($this->_attribs['extraClasses'])
            ? "class='{$this->_attribs['extraClasses']}'"
            : '';
        $html = <<<HTML
<div id="{$this->_id}" {$classes}>
  <p>Este explorador no soporta la tecnología necesaria para utilizar este componente</p>
  <p>Le recomendamos descargue alguno de los siguientes, podrá descargarlos haciendo clíck</p>
  <ul>
    <li>
      <a href="http://www.mozilla.org/es-ES/firefox/?from=getfirefox?from=getfirefox" rel="nofollow">
        Firefox
      </a>
    </li>
    <li><a href="http://www.google.com/chrome?hl=es" rel="nofollow">Chrome</a></li>
    <li><a href="http://www.apple.com/safari/download/" rel="nofollow">Safari</a></li>
    <li><a href="http://windows.microsoft.com/es-ES/internet-explorer/products/ie/home"
           rel="nofollow">Internet Explorer 9</a></li>
  </ul>
</div>
HTML;
        $this->view->headScript()->appendFile($this->_attribs['javascriptFile']);
        return $html;
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}
