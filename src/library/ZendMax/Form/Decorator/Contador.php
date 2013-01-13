<?php
namespace ZendMax\Form\Decorator;
require_once 'Zend/Form/Decorator/Abstract.php';
class contador extends \Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof \Zend_Form_Element || null === $element->getView()) {
            return $content;
        }
        $output = <<<HTML
<div style="float:right">
    <i>(Caracteres máximos: 125)</i> -
    Le quedan
    <input type="text" value="125" size="3" style="padding-top:0;padding-bottom:0; width:9%"
        name="countdown" readonly="" disabled="disabled"
    />
    caracteres.
</div>
HTML;
        return $output . $content;
    }
}