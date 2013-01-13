<?php
/**
 * @see ZendX_JQuery_Form_Element_UiWidget
 */
require_once "ZendX/JQuery/Form/Element/UiWidget.php";
/**
 * Form Element for jQuery DatePicker View Helper
 *
 * @package    ZendX_JQuery
 * @subpackage Form
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class My_JQuery_Form_Element_ColorPicker extends ZendX_JQuery_Form_Element_UiWidget
{
    public $helper = "formHidden";
    
    public function init()
    {
        print_r($this->setId('xx'));die;
        $params = $this->getJQueryParams();
        if (!isset($params['onChange'])) {
            $params['onChange'] = new Zend_Json_Expr('
                function() {
                    console.log("aca");
                }'
            );
            $this->setJQueryParams($params);
        }
    }
    
    /**
     * Load default decorators
     *
     * @return void
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('UiWidgetElement')
                 ->addDecorator(
                      array('hiddenValue' => 'HtmlTag'),
                      array(
                          'tag' => 'input',
                          'type' => 'hidden',
                          'value' => 'ff',
                          'name' => $this->getName()
                      )
                  )
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                 ->addDecorator('HtmlTag', array('tag' => 'dd'))
                 ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
}
