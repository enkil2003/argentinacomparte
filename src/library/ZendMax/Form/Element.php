<?php
namespace ZendMax\Form;
class Element extends \Zend_Form_Element
{
    /**
     * Set element attribute
     *
     * @param  string $name
     * @param  mixed $value
     * @return Zend_Form_Element
     * @throws Zend_Form_Exception for invalid $name values
     */
    public function setAttrib($name, $value)
    {
        $view = $this->getView();
        if ($view->doctype()->isHtml5() && $name === 'placeholder') {
            $value = $view->translate($value);
        }
        return parent::setAttrib($name, $value);
    }
}
