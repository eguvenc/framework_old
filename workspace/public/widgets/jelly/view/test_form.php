<!DOCTYPE html>
<html>
    <head>
        <title>Test Form</title>
        <meta charset="utf-8">

        <link rel="stylesheet" href="/assets/jelly/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/jelly/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/assets/jelly/css/style.css">

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
                        element.parentNode.insertBefore(e, element);
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
                    if (captcha) {
                        captcha.value='';
                    }
                    if(typeof obj['success'] !== 'undefined' && obj['success'] == '1'){ // Success
                        if(typeof obj['message'] !== 'undefined'){
                            document.getElementById("response").innerHTML = obj['message'];
                            document.getElementById("response").className = "alert alert-success";
                        }
                    } else { // Assign Test Results
                        document.getElementById("response").innerHTML = obj['message'];
                        document.getElementById("response").className = "alert alert-danger";
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
        <div class="container">
            <div class="row">

                <div class="page-header"><h4>Test Form</h4></div>

                <?php echo $this->form->getMessage(); ?>
                <div id="response"><!-- A message for a specified form will apear here --></div>
                <hr>

                <?php print($this->jellyForm->printForm()); ?>
                
            </div>      
        </div>
    </body>
</html>