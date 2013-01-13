<?php
/**
 * Vassilymas
 *
 * @license - $License: http://www.gnu.org/licenses/gpl.txt $
 *
 * @author - $Author: enkil2003 $
 * @date - $Date: 2012-01-04 02:30:02 -0300 (Wed, 04 Jan 2012) $
 * 
 * @filesource - $HeadURL: https://subversion.assembla.com/svn/vassilymas/trunk/library/My/Form/Decorator/Form.php $
 * @revision - $Revision: 364 $
 * 
 * @LastChangedBy $LastChangedBy: enkil2003 $
 * @lastChangedDate - $LastChangedDate: 2012-01-04 02:30:02 -0300 (Wed, 04 Jan 2012) $
 * 
 * @copyright - Copyright: (c) 2011 and future, Ricardo Buquet
 */

require_once 'Zend/Form.php';
class My_Form_Decorator_JsAutoValidation extends Zend_Form_Decorator_Abstract
{
    /**
     * Namespace holding the javascript
     * @var String Namespace
     */
    protected $_javascriptNamespace = 'My';
    
    /**
     * The javascript file validator path
     * @var String javascript validator path
     */
    protected $_javascriptValidatorPath = '/js/Validator.js';
    
    /**
     * Holds the reference for the element that should trigger the validation
     * @var string DOM's selector's name
     */
    protected $_validationTriggerSelector = null;
    
    /**
     * Returns the javascript namespace
     * @return string Namespace
     */
    public function getJsNamespace()
    {
        return $this->_javascriptNamespace;
    }
    
    /**
     * Sets the javascript namespace
     * @param string $namespace namespace
     */
    public function setJsNamespace($namespace)
    {
        $this->_javascriptNamespace = $namespace;
    }
    
    /**
     * Retrieves element decoratos for js
     * @param Zend_Form_Element|Zend_Form_DisplayGroup|Zend_Form $element
     * @return array
     */
    protected function _getElementErrorDecorator($element)
    {
        $decorators = array();
        if (
            $element instanceof Zend_Form_DisplayGroup ||
            $element instanceof Zend_Form
        ) {
            foreach($element->getElements() as $e) {
                if ($decorator = $this->_getElementErrorDecorator($e)) {
                    $decorators[] = $decorator;
                }
                unset($decorator);
            }
            return $decorators;
        }
        // does this element have an error decorator?
        if (!($errorDecorator = $element->getDecorator('Errors'))) {
            return;
        }
        
        $formErrorsHelper = $element->getView()->getHelper('formErrors');
        
        if ($formErrorsHelper instanceof Zend_View_Helper_FormErrors) {
            // 'errors' is the default class for elements in Zend_View_Helper_FormErrors
            // I can't ask the helper for that param, so it's hardcoded here
            $errorDecorator->setOption(
                'class',
                $errorDecorator->getOption('class')? $errorDecorator->getOption('class'):'errors' 
            );
        }
        
        $return = array(
            'errorDecorator' => array(
                'separator' => $errorDecorator->getSeparator(), // \n
                'placement' => $errorDecorator->getPlacement() // APPEND
            ),
            'formErrorsHelper' => array(
                'elementStart' => str_replace('%s', '', $formErrorsHelper->getElementStart()), // <ul%s><li>
                'elementSeparator' => $formErrorsHelper->getElementSeparator(), // </li><li>
                'elementEnd' => $formErrorsHelper->getElementEnd() // </li></ul>
            ),
            'options' => $errorDecorator->getOptions()
        );
        return $return;
    }
    
    /**
     * Retrieves element filters for js
     * @param Zend_Form_Element|Zend_Form_DisplayGroup|Zend_Form $element
     * @return array
     */
    protected function _getElementFilters(Zend_Form_Element $element)
    {
        $filters = array();
        if (!($filtersArr = $element->getFilters())) {
            return;
        }
        foreach($filtersArr as $filter) {
            $classArr =  explode('_', get_class($filter));
            $filters[] = array_pop($classArr);
        }
        
        return $filters;
    }
    
    /**
     * Return the javascript equivalent for the element validators
     * @param Zend_Form_Element | Zend_Form_DisplayGroup | Zend_Form $element
     * @return string the javascript validators
     */
    protected function _getElementValidators($element)
    {
        $validatorsRules = array();
        if (
            $element instanceof Zend_Form_DisplayGroup ||
            $element instanceof Zend_Form
        ) {
            foreach($element->getElements() as $e) {
                $validatorsRules[] = $this->_getElementValidators($e);
            }
            return $validatorsRules;
        }
        
        $validators = $element->getValidators();
        // Zend_Form_Element::setRequired(true) won't add the NotEmpty validator until isValid
        // is called, so we must force the validator inclusion as the first validator
        if($element->isRequired() && !$element->getValidator('NotEmpty')) {
            $rememberedValidators = array(array('NotEmpty', true));
            foreach($validators as $validator) {
                $rememberedValidators[] = $validator;
                $classArr = explode('_', get_class($validator));
                $validatorName = array_pop($classArr);
                $element->removeValidator($validatorName);
            }
            $element->addValidators($rememberedValidators);
            $validators = $element->getValidators();
        }
        
        $return = null;
        // retrieve validation rules if we have a validator
        if(count($validators) > 0) {
            $return = $this->_buildValidationRules($element);
        }
        return $return;
    }
    
    /**
     * Retrieve the validators and prepare a json object to share validator params with javascript
     * @return void
     */
    protected function _buildJsVars()
    {
        $elementsToJs = array();
        $formErrorDecorator = null;
        $formElement = $this->getElement();
        if ($formErrors = $formElement->getDecorator('FormErrors')) {
            $formErrorDecorator = $this->_getFormErrorsDecorator();
        }
        // create variable to share with the Validator.js
        $elementsErrorDecorators = array();
        foreach($formElement->getElements() as $element) {
            if($validators = $this->_getElementValidators($element)) {
                $elementsToJs[$element->getName()]['validators'] = $validators;
            }
            if ($decorators = $this->_getElementErrorDecorator($element)) {
                $elementsToJs[$element->getName()]['decorators'] = $decorators;
            }
            if ($filters = $this->_getElementFilters($element)) {
                $elementsToJs[$element->getName()]['filters'] = $filters;
            }
        }
        $formErrorDecorator = Zend_Json::encode($formErrorDecorator);
        $formErrorDecorator = str_replace('\/', '/', $formErrorDecorator);
        
        $elementsToJs = Zend_Json::encode($elementsToJs);
        $elementsToJs = str_replace('\/', '/', $elementsToJs);
        
        $elementId = $formElement->getId();
        
        // inject javascript
        $return = <<<SCRIPT
var {$this->_javascriptNamespace} = {$this->_javascriptNamespace} || {};
{$this->_javascriptNamespace}.Forms = {$this->_javascriptNamespace}.Forms || {};
{$this->_javascriptNamespace}.Forms.{$elementId} = {};
{$this->_javascriptNamespace}.Forms.{$elementId}.elements = {$elementsToJs};
{$this->_javascriptNamespace}.Forms.{$elementId}.FormErrorDecorator = {$formErrorDecorator};

SCRIPT;
        $view = $formElement->getView();
        $view->inlineScript()->captureStart();
        echo $return;
        $view->inlineScript()->captureEnd();
    }
    
    /**
     * Generate the JavaScript code for the validation rules
     * @param Zend_Form_Element $element
     * @return string
     */
    protected function _buildValidationRules(Zend_Form_Element $element)
    {
        $jsNamespace = $this->getJsNamespace();
        
        $formName = $this->getElement()->getId();
        $name = $element->getName();
        
        $validatorsRules = array();
        // if the element is not required, flag it so the validator knows that it should break the validation chain if the field has no value
        if(!$element->isRequired()) {
            $validatorsRules['NotRequired'] = TRUE;
        }
        
        $validators = $element->getValidators();
        foreach($validators as $validator) {
            $classnameArr = explode('_', get_Class($validator));
            // obtain name without the namespace, so we can get a more generic validator name, and replace it with anything we want
            $class = array_pop($classnameArr);
            $validatorParams = $this->_buildValidatorParameters($validator);
            $validatorsRules[$class] = $validatorParams;
        }
        if(count($validatorsRules) > 0) {
            $return = $validatorsRules;
        }
        return $return;
    }
   
    /**
     * Generate parameters for a validator rule
     * @param Zend_Validate_Interface $validator the validator
     * @return array
     */
    protected function _buildValidatorParameters(Zend_Validate_Interface $validator)
    {
        $params = array();
        $_removeMethods = array('getOptions', 'getCount');
        
        foreach(get_class_methods($validator) as $method) {
            if (substr($method, 0, 3) !== 'get' || in_array($method, $_removeMethods)) {
                continue;
            }
            $return = $validator->$method();
            if (!is_object($return)) {
                // lowercase first letter
                $keyname = substr($method, 3);
                $start = strtolower($keyname{0});
                $keyname = $start . substr($keyname, 1);
               
                $params[$keyname] = $return;
            }
        }
        
        if (property_exists($validator, 'zfBreakChainOnFailure')) {
            $params['breakChainOnFailure'] = $validator->zfBreakChainOnFailure;
        }
        
        // delete repetead messages, as in EmailAddress
        $params['messageTemplates'] = array_unique($params['messageTemplates']);
        return $params;
    }

    /**
     * This method is intented to be overrided and to extend the functionality.
     * as Zend does with init() methods.
     */
    protected function getValidatorOptions()
    {
        //
    }
    
    protected $_formId = null;
    
    public function getFormId()
    {
        if (null === $this->_formId) {
            $formElement = $this->getElement();
            $this->_formId = ($formId = $formElement->getId())? $formId:uniqid('form');
            $formElement->addAttribs(array('id' => $this->_formId));
        }
        return $this->_formId;
    }
    
    /**
     * Injects the validation event in the inlineScript helper, and appends the Validator.js file
     * @return void
     */
    public function render($content)
    {
        $formElement = $this->getElement();
        $view = $formElement->getView();
        
        $formId = $this->getFormId();
        $options = $this->getOptions();
        if ($triggerElement = $this->getOption('trigger')) {
            $trigger = $formElement->getElement($triggerElement)->getName();
            $event = ($event = $this->getOption('event'))? $event:'click';
            $selector = "\"[name='$trigger']\",\"#{$formId}\"";
            $trigger = <<<SCRIPT
$("[name='{$trigger}']", "#$formId").unbind('click.JsAutoValidation').click()
SCRIPT;
        } else {
            $event = 'submit';
            $trigger = <<<SCRIPT
$('#{$formId}').unbind('submit.JsAutoValidation').submit()
SCRIPT;
            $selector = "'#{$formId}'";
        }
        $validatorOptions = isset($options['validatorOptions'])
            ? Zend_Json::encode($options['validatorOptions'], false, array('enableJsonExprFinder' => true))
            : '{}';
        
        $view->inlineScript()->captureStart();
        echo "$(function() {
    $({$selector}).bind(
        '{$event}.JsAutoValidation',
        function(e) {
            alert('x');
            e.preventDefault();
            return;
            if(
                {$this->_javascriptNamespace}.Form.validate(
                    $('#{$formId}'),
                    {$validatorOptions}
                )
            ) {
                {$trigger};
            }
        }
    );
});";
        $view->inlineScript()->captureEnd();
        $view->headScript()->appendFile($this->_javascriptValidatorPath);
        $this->_buildJsVars();
        return $content;
    }
    
    /**
     * Sets the element that triggers the validation
     * @param string $validationTriggerSelector a valid element in the form
     * @throws Zend_Exception
     * @return My_Form_Decorator_JsAutoValidation
     */
    public function setValidationTriggerSelector($validationTriggerSelector)
    {
        if (!$this->getElement()->getElement($validationTriggerSelector)) {
            throw new Zend_Exception("{$validationTriggerSelector} element was not found");
        }
        $this->_validationTriggerSelector = $validationTriggerSelector;
        return $this;
    }
    
    /**
     * Retrieves form decoratos for js
     * @return array
     */
    protected function _getFormErrorsDecorator()
    {
        $formElement = $this->getElement();
        $formErrorsHelper = $formElement->getView()->getHelper('formErrors');
        $formErrors = $formElement->getDecorator('FormErrors');
        $labels = array();
        foreach($formElement->getElements() as $element) {
            $labels[$element->getName()] = $element->getLabel();
        }
        $errorDecorator = array(
            'elementLabelEnd' => $formErrors->getMarkupElementLabelEnd(),
            'elementLabelStart' => $formErrors->getMarkupElementLabelStart(),
            'listEnd' => $formErrors->getMarkupListEnd(),
            'listItemEnd' => $formErrors->getMarkupListItemEnd(),
            'listItemStart' => $formErrors->getMarkupListItemStart(),
            'listStart' => $formErrors->getMarkupListStart(),
            'placement' => $formErrors->getPlacement(),
            'separator' => $formErrors->getSeparator(),
            'ignoreSubForms' => $formErrors->ignoreSubForms(),
            'showCustomFormErrors' => $formErrors->getShowCustomFormErrors(),
            'onlyCustomFormErrors' => $formErrors->getOnlyCustomFormErrors(),
            'labels' => $labels,
            'formErrorsHelper' => array(
                'elementStart' => str_replace('%s', '', $formErrorsHelper->getElementStart()), // <ul><li>
                'elementSeparator' => $formErrorsHelper->getElementSeparator(), // </li><li>
                'elementEnd' => $formErrorsHelper->getElementEnd() // </li></ul>
            )
        );
        return $errorDecorator;
    }
}

