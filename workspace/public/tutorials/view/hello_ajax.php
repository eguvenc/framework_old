<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo $this->html->css('welcome.css') ?>
        <title>Hello Ajax</title>

<script type="text/javascript">
var ajax = {
    post : function(url, closure, params){
        var xmlhttp;
        if (window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
        }
        xmlhttp.onreadystatechange=function(){
        /**
         * onreadystatechange will fire five times as 
         * your specified page is requested.
         * 
         *  0: uninitialized
         *  1: loading
         *  2: loaded
         *  3: interactive
         *  4: complete
         */
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
    //-------------------------------------------------------
    
    // Parse Errors
    if(typeof obj['success'] !== 'undefined' && obj['success'] == '0'){
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
    // Captcha refresh
    var captcha = myform.captcha;
    if (captcha) {
        myform.captchaImg.src="/index.php/widgets/captcha/create/'" + Math.random();
    }
    myform.onsubmit = function(){
        var elements = new Array();
        elements[0] = myform.getElementsByTagName('input');
        elements[1] = myform.getElementsByTagName('select');
        elements[2] = myform.getElementsByTagName('textarea');

        var elementsClass = document.getElementsByClassName('_inputError');
        
        //--------------- Ajax ----------------//

        ajax.post( myform.getAttribute('action'), function(json){
            var obj = JSON.parse(json);

            // Reset captcha value
            if (captcha) {
                captcha.value='';
            }

            // Success
            
            if(typeof obj['success'] !== 'undefined' && obj['success'] == '1'){
                if(typeof obj['message'] !== 'undefined'){
                    alert(obj['message']);
                    return false;
                }
                document.getElementById("response").innerHTML = '<pre>' + json.toString() + '</pre>';
            }
            else  // Assign Test Results
            {
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
        return false; // Do not do form submit;
    }
}
</script>

    </head>
    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hello Ajax</h1>
        <section>

            <form action="http://framework/index.php/tutorials/hello_ajax" method="POST" id="ajax_tutorial" />
                <table width="100%">
                    <tr>
                        <td style="width:20%;">Email</td>
                        <td><input type="text" name="email" value="" /></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="password" value="" /></td>
                    </tr>
                    <tr>
                        <td>Confirm</td>
                        <td><input type="password" name="confirm_password" value="" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <input type="checkbox" name="agreement" value="1" id="agreement"><label for="agreement"> I agree terms and conditions</label>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="dopost" value="DoPost" onclick="submitAjax('ajax_tutorial')" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>
            </form>

            <h2>Test Results</h2>
            <section>
                <h3>Json Response</h3>
                <div id="response"><pre></pre></div>
            </section>

        </section>

        <section>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </section>

    </body>
</html>