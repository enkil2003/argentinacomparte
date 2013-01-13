<?php
class My_View_Helper_ConfigJs extends Zend_View_Helper_Abstract
{
    protected $_simpleVars = null;

    public function configJs()
    {
        return $this;
    }
    
    public function setSimpleVars(array $varsToJs = array())
    {
        $this->_simpleVars = $varsToJs;
    }
    
    public function __toString()
    {
        return $this->_render();
    }
    
    protected function _render()
    {
        $json = Zend_Json::encode($this->_simpleVars);
        $tag = <<<HTML
<script type="text/javascript">
var ConfigJs = $json;
</script>
HTML;
        return $tag;
    }
}
