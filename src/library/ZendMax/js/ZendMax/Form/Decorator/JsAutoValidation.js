var ZendMax = ZendMax || { };
ZendMax.Form = ZendMax.Form || { };

ZendMax.Form.validate = function(form, options)
{
    // should still be the default form? i'm creating a random in backend if no one is given
    var formId = (form.attr('id')) ? form.attr('id') : 'form';
    
    var settings = $.extend(
        {
            beforeValidate: function() {
                //
            },
            invalid: function(element, errorMessages) {
                var elementName = element.attr('name').replace('[]', '');
                var decorators = ZendMax.Forms[formId].elements[elementName].decorators,
                    length,
                    iteration,
                    options
                ;
                
                errorMessagesContainer = decorators.formErrorsHelper.elementStart;
                length = errorMessages.length;
                iteration = 0;
                for (var i in errorMessages) {
                    errorMessagesContainer += errorMessages[i];
                    iteration++;
                    
                    if(iteration !== length) {
                        errorMessagesContainer += decorators.formErrorsHelper.elementSeparator;
                    }
                }
                errorMessagesContainer += decorators.formErrorsHelper.elementEnd;
                
                element.parent().find('.errors').remove();
                
                switch(decorators.errorDecorator.placement) {
                    case 'APPEND':
                        element.parent().append(errorMessagesContainer);
                        break;
                    default:
                        break;
                }
                element.parent().find('ul').attr(decorators.options).addClass('errors');
                
                if (settings.onAfterInvalidElement) {
                     settings.onAfterInvalidElement(element, errorMessages);
                }
            }, 
            valid: function(element) {
                element.parent().find('.errors').remove();
                
                if (settings.onAfterValidElement) {
                    settings.onAfterValidElement(element);
               }
            },
            onBeforeSubmit: function() {
                
            }
        },
        options);
    
    settings.beforeValidate();
    var formValidates = true;
    for(var elementId in ZendMax.Forms[formId].elements) {
        var element = $("#"+elementId, form),
            formElements = ZendMax.Forms[formId].elements,
            i;
        
        // filters
        for(var filterIndex in formElements[elementId].filters) {
            var filterName = formElements[elementId].filters[filterIndex];
            if (!ZendMax.Form.Filter[filterName]) {
                console.log("Warning: No Js Filter equivalent was found for - " + filterName +" - , skipping filter");
                continue;
            }
            element.val(ZendMax.Form.Filter[filterName](element.val()));
        }
        
        // validators
        var errorMessages = [];
        for(var validatorName in formElements[elementId].validators) {
            var validatorParameters = formElements[elementId].validators[validatorName];
            
            //if (validatorName === 'NotRequired' && element.val() === '') {
            if (validatorName === 'NotRequired' && formElements[elementId].validators.NotRequired === true && element.val() === '') {
                break; // break current validation
            }
            
            if (!ZendMax.Form.Validator[validatorName]) {
                console.log("Warning: No Js Validator equivalent was found for - " + validatorName +" - , skipping validation rule");
                continue;
            }
            
            // validate
            var result = ZendMax.Form.Validator[validatorName](element.val(), validatorParameters);
            if (true !== result.isValid) {
                errorMessages.push(result.errorMessage);
                formValidates = false;
                // break chain
                if (validatorName == 'NotEmpty' && validatorParameters.breakChainOnFailure) {
                    break;
                }
                
                if (validatorParameters.breakChainOnFailure) {
                    break;
                }
            }
        }
        if (errorMessages.length === 0) {
            settings.valid($(element));
        } else {
            settings.invalid($(element), errorMessages);
        }
    }
    if (formValidates) {
        if (settings.onBeforeSubmit) {
            settings.onBeforeSubmit(this);
        }
    } else {
        if (settings.onValidationFails) {
           settings.onValidationFails(form, settings); 
        }
    }
    return formValidates;
};

ZendMax.Form.Filter = {
    StringTrim: function(value) {
        return value.replace(/^\s+|\s+$/g, '');
    },
    Digits: function(value) {
        return value.replace(/[^0-9-]/g, '');
    }
};

ZendMax.Form.Validator = {
    
    // Helper functions
    
    /**
     * Retrieves the error messages from the validator params
     * @return string formatted error message
     */
    _prepareErrorMessages : function(errorMessage, value, params) {
        var messageVariables = this._prepareMessageVariables(params);
        if (null === errorMessage) {
            errorMessage = 'invalid field';
            console.log("Warning: No message was received, so 'invalid field' was placed insted");
        }
        for (var i in messageVariables) {
            if (errorMessage.replace) {
                errorMessage = errorMessage.replace('%'+i+'%', messageVariables[i]);
            }
        }
        errorMessage = errorMessage.replace('%value%', value);
        return errorMessage;
    },
    
    /**
     * Replaces place holders for real values
     *   ie: '%min% is less than N caracters long
     *   will be converted to '3' is less 6 caracters long
     * @param params object Object with message variables properties.
     *   ie: max: 50, min: 20
     * @return array Error messages variables
     */
    _prepareMessageVariables: function(params) {
        var messageVariables = [];
        for(var i in params.messageVariables) {
            messageVariables[params.messageVariables[i]] = params[params.messageVariables[i]];
        }
        return messageVariables;
    },
    
    // Concrete Validators
    NotEmpty: function(value, params) {
        var _return = {isValid: true};
        
        if (value === '' || value === null) {
            _return.isValid = false;
            _return.errorMessage = params.messageTemplates.isEmpty;
        }
        return _return;
    },
    
    EmailAddress: function(value, params) {
        var _return = {isValid: true};
        
        var emailRegex = /^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i;
        if (emailRegex.test(value)) {
            return _return;
        }
        _return.isValid = false;
        _return.errorMessage = params.messageTemplates.emailAddressInvalid;
        return _return;
    },
    
    StringLength: function(value, params) {
        var _return = {isValid: true};
        var errorMessage = null;
        
        if (value.length < params.min) {
            _return.isValid = false;
            errorMessage = params.messageTemplates.stringLengthTooShort;
        }
        if (value.length > params.max) {
            _return.isValid = false;
            errorMessage = params.messageTemplates.stringLengthTooLong;
        }
        if (_return.isValid === false) {
            _return.errorMessage = this._prepareErrorMessages(
                errorMessage,
                value,
                params);
        }
        return _return;
    },
    
    Identical: function(value, params) {
        var _return = {isValid: true};
        var tokenValue = $('#' + params.token).val();
        
        if(tokenValue === '') {
            _return.isValid = false;
            errorMessage = params.messageTemplates.missingToken;
        }
        
        if(value !== tokenValue) {
            _return.isValid = false;
            errorMessage = params.messageTemplates.notSame;
        }
        
        if (_return.isValid === false) {
            _return.errorMessage = this._prepareErrorMessages(
                errorMessage,
                value,
                params);
        }
        return _return;
    },
    
    Extension: function(value, params) {
        var _return = {isValid: true};
        if ($.inArray(value.split('.').pop(), params.extension) === -1) {
            _return.isValid = false;
        }
        
        if (!_return.isValid) {
            _return.errorMessage = this._prepareErrorMessages(
                params.messageTemplates.fileExtensionFalse,
                value,
                params);
        }
        return _return;
    },
    
    Dependency: function(value, params) {
        var _return = {isValid: true};
        var tokenValue = $('#' + params.token).val();
        
        if (
            (params.addValidatorsIfTokenValueProvided && tokenValue) ||
            (!params.addValidatorsIfTokenValueProvided && !tokenValue)
        ) {
            for (validator in params.dependantRules) {
                var rules = params.dependantRules[validator];
                var returnMessage = ZendMax.Form.Validator[validator](value, rules);
                if (!returnMessage.isValid) {
                    return returnMessage;
                }
            }
        }
        return _return;
    }
};
