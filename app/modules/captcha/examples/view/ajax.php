<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="<?php echo $this->url->asset('css/welcome.css') ?>" rel="stylesheet" type="text/css" />
        <title>Captcha</title>

<?php echo $this->captcha->printJs() ?>

<script type="text/javascript">
var ajax = {
    post : function(url, closure, params){
        var xmlhttp;
        xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                if( typeof closure === 'function'){
                    closure(xmlhttp.responseText);
                }
            }
        }
        xmlhttp.open("POST",url,true);
        xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xmlhttp.send(params);
    },
    get : function() {
        // paste here
    }
}
function parseNode(obj, element, i){
    var name = element.name;
    var inputError = document.getElementById(i + '_inputError');

    if(typeof obj['success'] !== 'undefined' && obj['success'] == '0'){      // Parse Errors
        if(typeof obj.errors[name] !== 'undefined'){
            if(inputError){
                document.getElementById(i + '_inputError').innerHTML = obj.errors[name];
            } else{
                e.innerHTML = obj.errors[name];
                element.parentNode.appendChild(e);
            }
        } else {
            if(inputError){
                document.getElementById(i + '_inputError').remove(); 
            }
        }
    } else {
        if(inputError){
            document.getElementById(i + '_inputError').remove(); 
        }
    }
}

function submitAjax(formId){
    var myform = document.getElementById(formId);

    myform.onsubmit = function(){
        var elements = new Array();
        elements[0] = myform.getElementsByTagName('input');
        elements[1] = myform.getElementsByTagName('select');
        elements[2] = myform.getElementsByTagName('textarea');

        var elementsClass = document.getElementsByClassName('_inputError');
        ajax.post( myform.getAttribute('action'), function(json){
            var obj = JSON.parse(json);

            if(typeof obj['success'] !== 'undefined' && obj['success'] == '1'){  // Success
                if(typeof obj['message'] !== 'undefined'){
                    alert(obj['message']);
                    return false;
                }
                document.getElementById("response").innerHTML = '<pre>' + json.toString() + '</pre>';
            }
            else  // Assign Test Results
            {
                oResetCaptcha(myform);
                document.getElementById("response").innerHTML = '<pre>' + json.toString() + '</pre>';
            }

            for (var i = 0; i < elements.length; i++){
                var elemets2 = new Array();
                    elemets2 = elements[i];
                for(var ii = 0; ii < elemets2.length; ii++){
                    e = document.createElement('div');
                    e.className = '_inputError';
                    errorInputNameId = i.toString() + ii.toString();
                    e.id = errorInputNameId + '_inputError';
                    if (elemets2[ii].type != 'submit'){
                        if ( elemets2[ii].type != 'hidden') {
                            parseNode(obj, elemets2[ii], errorInputNameId);
                        }
                    }
                }
            }
        },
        new FormData(myform)
        );
        return false; // Stop form submit action;
    }
}
</script>
</head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?>
        </header>

        <h1>Captcha Ajax Example</h1>

        <section>
            <form action="/captcha/examples/ajax" method="POST" id="ajax_captcha">
                <table width="100%">
                    <tr>
                        <td style="width:20%;"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td>
                        <?php echo $this->form->getError('username') ?>
                        <input type="text" name="username" value="<?php echo $this->form->getValue('username')?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Captcha</td>
                        <td>
                        <?php echo $this->form->getError($this->captcha->getInputName()) ?>
                        <?php echo $this->captcha->printHtml() ?>
                        <?php echo $this->captcha->printRefreshButton() ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="dopost" value="DoPost" onclick="submitAjax('ajax_captcha');"  /></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                </form>

                <h2>Form Results</h2>


                <h2>Test Results</h2>
                <section>
                    <h3>Json Response</h3>
                    <div id="response"><pre></pre></div>
                </section>

        </section>

        <p>&nbsp;</p>

    </body>
</html>