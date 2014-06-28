/**
 * @author Rabih Abou Zaid <rabihsyw@gmail.com>
 * Obullo Framework.
 * FORM_FACTORY
 * 
 * Dependency : Jquery
 *              UnderscoreJs
 *
 * Usage      : 
 *              * var formObj = FORM_FACTORY.create('form-id');
 *                the returned valued is an object of 'form' class form.js
 *              * var formObj = FORM_FACTORY.createTemplate('container-id', jsonForm);
 *                the returned valued is an object of 'form' class form.js
 *             
 * 
 * @type Object
 */


var FORM_FACTORY = new function (){
    var forms = {};
    return {
        create : function(formId, showErrors, liveValidator) {
            
            if (! forms.formId) {

                forms[formId] = new FORM(formId, showErrors, liveValidator);

            }

            return forms[formId];
        },
        createTemplate : function (tplName, jsonData, containerId) { /* this function builds form template */

            try {
                jsonData = jQuery.parseJSON(jsonData);
            } catch(error) {

            }
            
            var formHtml = TEMPLATE.getParsed(tplName, jsonData);

            $(document).ready(function(){
                $('#'+containerId).html(formHtml);
            });
            
            /* get form info. Important */
            var formId = (jsonData.form.id) ? jsonData.form.id : 'hasnoid';

            return this.create(formId, true);

            /* obj.formId = 'frm'+ Math.floor((Math.random()*10)+1); */
        }
    }
};

/**
 * @author Rabih Abou Zaid <rabihsyw@gmail.com>
 * Obullo Framework.
 * Form handling class & validating plugin
 * 
 * Dependency : Jquery
 *
 * __Construct: new form(attr1, attr2, attr3) : 
 *              attr1 : string : form ID ie : <form id='form-id'></form>
 *              attr2 : bool   : default true,  determine to show form errors or not
 *              attr3 : bool   : default false, determine to validate each input on focuse-out event.
 *
 * functions :
 *              setLoading(param1)                      : param1      = function
 *              whenSubmit(afterSubmit, beforeSubmit)   : afterSubmit = function, beforeSubmit = function
 *              setMessage(msg)                         : msg         = string
 *              setError(inputName, msg, ref)           : inputName   = string, msg = string, ref = string
 *              
 * Attributes:
 *              formElement : jquery-object for form
 *              formId      : string
 *              response    : json or string
 *                 
 * 
 * @type Object
 */
var FORM = function(formId, showErrors, liveValidator){
    var obj = {
        formId                 : formId,
        liveValidator          : (typeof liveValidator == 'boolean') ? liveValidator : false,
        show_errors            : (typeof showErrors == 'boolean') ? showErrors : true,
        /* default values */
        success                : true,             /* validation is success or not. */
        formElement            : '',               /* jquery-object for the form */
        containerId            : '',               /* form-container id */
        containerElement       : '',               /* jquery-object for the form container */
        response               : false,            /* response after ajax post */
        template               : '',               /* template */
        loading                : function (){},    /* closure for loading when ajax submit */
        emailRegex             : /^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i,
        passwordRegex          : /^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(\w{8,15})$/,
        error_float_span_class : 'glyphicon glyphicon-warning-sign form-control-feedback',
        correct_float_span_class : 'glyphicon glyphicon-ok form-control-feedback',
    };

    var getInputObj = function (input) { /* this function return jquery-object for an input-name */
        if(typeof input == 'string')
        {
            input = obj.formElement.find('*[name='+input+']');
        }
        return input;
    };

    var changeContainer = function() {

        if((! obj.containerId) || obj.containerElement.prop("tagName") == 'BODY') {
            obj.containerElement = obj.formElement.parent('*');

            obj.containerId = obj.containerElement.attr('id');

            if ( ! obj.containerId) {
                console.log('Auto generated container id for form : '+ obj.formId);
                obj.containerId = 'auto-'+ obj.formId;
                obj.containerElement.attr('id', obj.containerId);
            }
            
        }
    };

    obj.construct = function(){
        /* some statements here for constructor. */

        $(document).off('focus', 'form#'+obj.formId).on('focus','form#'+obj.formId, function () {
            obj.constructFormElement($(this));
        });
        
        obj.constructFormElement($('form#'+obj.formId));
    };

    obj.constructFormElement = function (form_obj) {
        
        if (obj.formElement.length == 0) {
            obj.formElement = $('form#'+obj.formId);
            changeContainer();

            if(obj.liveValidator) {
                obj.formElement.off('focusout').on('focusout', 'input,select,textarea', function(){
                    obj.validateRules($(this).attr('name'), $(this).data('validate'));
                });
            } else {
                obj.formElement.off('focusout', 'input,select,textarea', function(){
                });
            }
        }
    };

    obj.construct();

    obj.setLoading = function(closure){
        if( typeof closure === 'function'){
            obj.loading = closure;
        }
    };

    obj.setSuccess = function(res){
        if(obj.success == true)
        {
            obj.success = res;
        }
    };


    obj.setError = function(input, msg, ref){ /* setting input's error message */
        inputObj = getInputObj(input);
        obj.setSuccess(false);

        obj.removeError(input , input);
        obj.removeCorrect(input, input);
        
        if (obj.show_errors) {

            var $form_error_obj = obj.formElement.find('[data-error-for="'+input+'"]');
            $form_error_obj.show();
            
            {   /* this line was added just in case of multiple error per row */
                $form_error_obj.parent('*').show(); 
                
                if($form_error_obj.length == 0) { /* added temporaty */
                    inputObj.after($(document.createElement('div')).attr({'data-error-for': input, 'class' : 'form-error'}));
                    $form_error_obj = obj.formElement.find('[data-error-for="'+input+'"]').show();
                }
            }

            if($form_error_obj.length > 0) {
                
                var error = $(document.createElement('div')).attr({'class' : '_error', 'data-input_ref' : input}).html(msg);

                $form_error_obj.append(error);
            }

        }

        var $feedback_parent = inputObj.parents('.has-feedback');
        if ($feedback_parent.length > 0) {

            var floatErrSpan = $(document.createElement('span')).attr({'class':obj.error_float_span_class, 'data-float_error' : input});

            if (inputObj.attr('type') != 'radio' && inputObj.attr('type') != 'checkbox' && inputObj.prop('tagName').toLowerCase() != 'select') {
                inputObj.after(floatErrSpan);
            }
            inputObj.parent().addClass('has-error');
        }
    };

    obj.setCorrect = function(input, ref){ /* setting input's error message */
        inputObj = getInputObj(input);

        obj.removeError(input, input);
        obj.removeCorrect(input, input);

        var $feedback_parent = inputObj.parents('.has-feedback');
        if ($feedback_parent.length > 0) {

            var floatErrSpan = $(document.createElement('span')).attr({'class':obj.correct_float_span_class, 'data-float_error' : input});

            if (inputObj.attr('type') != 'radio' && inputObj.attr('type') != 'checkbox' && inputObj.prop('tagName').toLowerCase() != 'select') {
                inputObj.after(floatErrSpan);
            }
            inputObj.parent().addClass('has-success');
        }
    };

    obj.removeError = function(input, ref){ /* setting input's error message */
        inputObj = getInputObj(input);

        var $form_error_obj = obj.formElement.find('[data-error-for="'+input+'"]');
        
        if ($form_error_obj.length > 0) {

            $form_error_obj.find("[data-input_ref='"+ref+"']").hide().remove();
            $form_error_obj.find("[data-float_error='"+input+"']").hide().remove();
            $form_error_obj.hide();
        }

        var $feedback_parent = inputObj.parents('.has-feedback');
        if ($feedback_parent.length > 0) {
            inputObj.parent().removeClass('has-error');
            inputObj.next('[class="'+obj.error_float_span_class+'"]').remove();
        }
    };

    obj.removeCorrect = function(input, ref){ /* setting input's error message */
        inputObj = getInputObj(input);

        var $feedback_parent = inputObj.parents('.has-feedback');
        if ($feedback_parent.length > 0) {
            inputObj.parent().removeClass('has-success');
            inputObj.next('[class="'+obj.correct_float_span_class+'"]').remove();
        }
    };

    obj.setMessage = function(msgText){ /* setting form message */
        if (obj.show_errors) {

            var $generalEl = $('[data-general-message="response"]');
            var $frmEl     = $(document).find('[data-message-form="'+obj.formId+'"]');

            $generalEl = ($frmEl.length > 0) ? $frmEl : $generalEl;

            if ($generalEl.length > 0) {
                var msgClass = (obj.response.success != 'undefined') ? ((obj.response.success == 1) ? 'alert alert-success' : ((obj.response.success == 2) ? 'alert alert-info' : 'alert alert-danger')) : 'alert alert-info';

                var $msg = $(document.createElement('div')).html(msgText).addClass(msgClass).hide(); /* creating message element. */
                console.warn('suppose to print message here');
                $generalEl.html($msg.fadeIn('slow'));
            }

            if (obj.response.success != null && obj.response.success != 0) {
                if (obj.msgTimeOut) {
                    clearTimeout(obj.msgTimeOut);
                    delete obj.msgTimeOut;
                }

                if (! obj.msgTimeOut) {
                    obj.msgTimeOut = setTimeout(function(){
                        obj.removeMessage();
                    }, 5000);
                }
            }

        }
    };

    obj.removeMessage = function () {
        var $generalEl = $('[data-general-message="response"]');
        var $frmEl     = $('[data-message-form="'+obj.formId+'"]');

        $generalEl = ($frmEl.length > 0) ? $frmEl : $generalEl;
        $generalEl.contents().slideUp();
        if (obj.msgTimeOut) {
            clearTimeout(obj.msgTimeOut);
        }
    };

    obj.removeAllErrors = function(){ /* delete input's error message */

        obj.formElement.parent('*').find('.form-message').hide().remove();
        obj.formElement.parent('*').find('.'+obj.error_msg_class).hide().remove();
    }

    obj.validateEmail = function(input){

        var ref = 'emailError';
        var res = true;

        inputObj = getInputObj(input);

        if( ! obj.emailRegex.test(inputObj.val()) )
        {
            obj.setError(input, 'Invalid email address', ref);
            res = false;
        }
        return res;
    };

    obj.validatePassword = function(input){
        var ref = 'passwordError';
        var res = true;

        inputObj = getInputObj(input);

        if( ! obj.passwordRegex.test(inputObj.val()) )
        {
            obj.setError(input, 'Must be between 8 and 15 characters having at least 1 digit, 1 small letter, 1 capital letter.', ref);
            res = false;
        }
        return res;
    };

    obj.requiredCheck = function(input){
        var ref = 'required';
        var res = true;

        inputObj = getInputObj(input);
        
        if(inputObj.attr('type') == 'radio' || inputObj.attr('type') == 'checkbox')
        {
            if(! inputObj.is(':checked'))
            {
                obj.setError(input, 'This field is required.', ref);
                res = false;
            }
        }
        else if(inputObj.val().trim() === null || inputObj.val().trim() === '')
        {
            obj.setError(input, 'This field is required.', ref);
            res = false;
        }
        return res;
    };

    obj.isInteger = function (input) {
        var ref = 'isInt';
        var intRegex = /^\d+$/;
        var res = true;

        inputObj = getInputObj(input);

        if(! intRegex.test(inputObj.val())) {
           obj.setError(input, 'This field must conatain an integer number.', ref);
           res = false;
        }
        return res;
    }

    obj.isDecimal = function (input) {
        var ref = 'isDeci';
        var res = true;

        inputObj = getInputObj(input);
        var val = inputObj.val();

        if(!isFinite(val) || !(/^\d+\.\d+$/.test(val))) {
           obj.setError(input, 'This field must conatain decimal number.', ref);
           res = false;
        }
        return res;
    }

    obj.isNumeric = function (input) {
        var ref = 'isNum';
        var res = true;

        inputObj = getInputObj(input);
        var val = inputObj.val();

        if(isNaN(parseFloat(val)) && !isFinite(val)) {
           obj.setError(input, 'This field must conatain numeric value.', ref);
           res = false;
        }
        return res;
    }

    obj.isString = function (input) {
        var ref = 'isInt';
        var strRegex = /^[a-zA-Z\u00C0-\u00ff]+$/;
        var res = true;

        inputObj = getInputObj(input);

        if(! strRegex.test(inputObj.val())) {
           obj.setError(input, 'Shouldn\'t contain numbers or special characters.', ref);
           res = false;
        }
        return res;
    }

    obj.matches = function(second, first){
        var ref = 'matches';
        var res = true;

        secondObj = getInputObj(second);
        firstObj = getInputObj(first);

        if(secondObj.val() != firstObj.val())
        {
            /* getting first input label */
            var lbl = obj.formElement.find('label[for='+firstObj.attr('name')+'],label[for='+firstObj.attr('id')+']');
            if (lbl.length == 0) 
                alert('FormJs : Missing \'for\' attribute for the label of this input : \''+firstObj.attr('name')+'\'. ');

            /* setting error */
            obj.setError(second, 'It doesn\'t match \''+ lbl.text()+'\'', ref);
            res = false;
        }
        return res;
    }

    obj.minLen = function(input, len){
        var ref = 'minlen';
        var res = true;

        inputObj = getInputObj(input);

        if(inputObj.val().length < len)
        {
            obj.setError(input, 'Must be at least '+len+' characters.', ref);
            res = false;
        }
        return res;
    }

    obj.maxLen = function(input, len){
        var ref = 'minlen';
        var res = true;

        inputObj = getInputObj(input);

        if(inputObj.val().length > len)
        {
            obj.setError(input, 'Must be maximum '+len+' characters.', ref);
            res = false;
        }
        return res;
    }

    obj.exactLen = function(input, len){
        var ref = 'exactlen';
        var res = true;

        inputObj = getInputObj(input);
        
        if(inputObj.val().length != len)
        {
            obj.setError(input, 'Length of this field must be exact '+len+' characters.', ref);
            res = false;
        }
        return res;
    }

    obj.isValid = function(){

        obj.constructFormElement();

        obj.success = true;

        obj.removeAllErrors();
        /*$('[data-general-message="response"]').contents().fadeOut();*/
        obj.removeMessage();
        
        var tempRules = obj.formElement.find("*[data-validate]");
        var rulesElements = {};

        tempRules.each(function(){
            if (! rulesElements.hasOwnProperty($(this).attr('name'))) {
                rulesElements[$(this).attr('name')] = $(this);
            }
        });

        for (var i in rulesElements) {
            obj.validateRules(rulesElements[i].attr('name'), rulesElements[i].data('validate'));
        }

        var first = obj.formElement.parent('*').find('.'+obj.error_msg_class+' > div:first').data('input_ref');
        obj.formElement.find("[name='"+first+"']").focus();

        return obj.success;
    };

    obj.validateRules = function(input, rules){

        obj.removeError(input);
        var pass = true;
        if (rules) {
            var str_split = rules.split('|');
            if(str_split.length > 0)
            {
                for(var i in str_split)
                {
                    if (pass == true) {

                        if(str_split[i] == 'required')
                        {
                            pass = obj.requiredCheck(input);
                        }

                        if(/email/i.test(str_split[i]))
                        {
                            pass = obj.validateEmail(input);
                        }

                        if(/^validPassword/i.test(str_split[i]))
                        {
                            pass = obj.validatePassword(input);
                        }

                        if(/^matches/i.test(str_split[i]))
                        {
                            var res = str_split[i].match( /matches\((.*?)\)/i );
                            
                            pass = obj.matches(input, res[1]);
                        }

                        if(/^minlen/i.test(str_split[i]))
                        {
                            var res = str_split[i].match( /minlen\((.*?)\)/i );
                            
                            if(res[1].length > 0)
                            {
                                pass = obj.minLen(input, res[1]);
                            }
                        }

                        if(/^maxlen/i.test(str_split[i]))
                        {
                            var res = str_split[i].match( /maxlen\((.*?)\)/i );
                            
                            if(res[1].length > 0)
                            {
                                pass = obj.maxLen(input, res[1]);
                            }
                        }

                        if(/^exactlen/i.test(str_split[i]))
                        {
                            var res = str_split[i].match( /exactlen\((.*?)\)/i );
                            
                            if(res[1].length > 0)
                            {
                                pass = obj.exactLen(input, res[1]);
                            }
                        }

                        if(/^isInteger/i.test(str_split[i]))
                        {
                            pass = obj.isInteger(input);
                        }

                        if(/^isdecimal/i.test(str_split[i]))
                        {
                            pass = obj.isDecimal(input);
                        }

                        if(/^isnumeric/i.test(str_split[i]))
                        {
                            pass = obj.isNumeric(input);
                        }
                    }

                }
            }
            if (pass) {
                obj.setCorrect(input);
            }
        }
        return pass;
    };

    obj.ajaxPost = function(closure){

        res = COMMON.ajax(obj.formElement.attr('action'), obj.formElement.formToJson(), {type : 'post', async : false}, function (res) {

        });

        try {
            obj.response = $.parseJSON(res);
        } catch (e) {
            obj.response = res;
        }

        if( typeof closure === 'function'){
            closure(res);
        }
    };

    obj.processErrorsResponse = function(errors){
        
        obj.constructFormElement();

        for(var i in errors){
            if(i != '')
            {
                obj.setError(i, errors[i], 'autoMsg');
            }

        }
        var first = obj.formElement.parent('*').find('.'+obj.error_msg_class+' > div:first').data('input_ref');
        obj.formElement.find("[name='"+first+"']").focus();
    };

    obj.processMessagesResponse = function(messages){

        obj.removeMessage();

        obj.constructFormElement();

        obj.formElement.parent('*').find('.form-message').remove();
        if($.isArray(messages)) {
            if(messages.length > 0){
                for(var i in messages)
                {
                    obj.setMessage(messages[i]);
                }
            }
        }else if(messages.length > 0){ /* this check to avoid error message if message is empty. */
            obj.setMessage(messages);
        }
    };
    
    obj.checkAjax = function () { /* check if ajax-post or normal-post */
        if(obj.formElement.data('ajax') == 1)
            return true;
        console.warn('This form wont be sent as ajax request. Please make sure of ajax attribute must be "data-ajax=\'1\'" ') /* for debugging */
        return false;
    };

    obj.whenSubmit = function(closureAfter, closureBefore){

        obj.closureAfter = closureAfter;
        obj.closureBefore = closureBefore;

        if(obj.formId == '') {
            console.error('There is no form to submitted, please make sure of declaring the form using : setFormId or createTemplate');
        }

        $(document).off('submit', "#"+obj.formId).on('submit', "#"+obj.formId, function(){

            if(obj.formElement.length == 0)
                obj.setFormId(obj.formId);

            var isValid = obj.isValid();
            

            var execute = true; /* beforeClosure return true or false; */

            if(typeof obj.closureBefore === 'function')
            {
                execute = obj.closureBefore();
            }

            console.log(obj.formId + ' -> submitting || Container id -> '+ obj.containerId);
            console.log('is vlaid : ' + (isValid && execute));

            if( isValid && execute )
            {

                if(obj.checkAjax())
                {

                    if(typeof obj.loading == 'function') {
                        obj.loading();
                    }
                    
                    console.log('sending xmlhttp-request ....');

                    obj.ajaxPost(function(res){

                        try {
                            res = $.parseJSON(res);
                        } catch(error) {
                            /* okay not json */
                        }

                        if (typeof obj.closureAfter == 'function') {
                            obj.closureAfter(res);
                        }

                        if(res.hasOwnProperty('redirect')){
                            window.location.replace(res.redirect);
                        }

                        if (obj.show_errors && obj.show_errors == true) {
                            if(res.hasOwnProperty('message'))
                            {
                                obj.processMessagesResponse(res.message);
                            }

                            if(res.hasOwnProperty('errors'))
                            {
                                obj.processErrorsResponse(res.errors);
                            }
                        }
                    });
                    return false;
                }
                return execute;
            }
            return false;
        });
    };

    obj.off = function () {
        $(document).off('submit', "#"+obj.formId);
        obj.formElement.off('focusout');
    };

    return obj;
};