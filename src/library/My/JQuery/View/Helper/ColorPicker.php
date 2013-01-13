<?php
require_once 'ZendX/JQuery/View/Helper/UiWidget.php';
class My_JQuery_View_Helper_ColorPicker extends ZendX_JQuery_View_Helper_UiWidget 
{
    public function colorPicker(
        $id, 
        $value = null,
        array $params = null,
        array $attribs = null
        )
    {
        $jquery = $this->view->jQuery();
        $jquery->enable();
        $jquery->addJavascriptFile('/js/jquery/plugins/colorPicker/js/colorpicker.js');
        $jquery->addStylesheet('/js/jquery/plugins/colorPicker/css/colorpicker.css');
        
        $attribs = $this->_prepareAttributes($id, $value, $attribs);
        $params = ZendX_JQuery::encodeJson($params);
        $js = sprintf('%s("#%s").ColorPicker(%s);',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $params
        );
        $this->jquery->addOnLoad($js);
        $htmlAttribs = $this->_htmlAttribs($attribs);
        return "<div $htmlAttribs></div>\n";
    }
    
    /**
     * Helps with building the correct Attributes Array structure.
     *
     * @param String $id
     * @param String $value
     * @param Array $attribs
     * @return Array $attribs
     */
    protected function _prepareAttributes($id, $value, $attribs)
    {
        $attribs = parent::_prepareAttributes($id, $value, $attribs);
        unset($attribs['value']);
        return $attribs;
    }
}