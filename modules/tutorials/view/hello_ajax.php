<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <?php echo Html::css('welcome.css') ?>

        <title>Odm Ajax Tutorial</title>

<script type="text/javascript">

var ajax = {
   post : function(url, closure, params) {
        var xmlhttp,response;
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
        xmlhttp.open("POST",url);
        // xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // xmlhttp.setRequestHeader("Content-length", params.length);
        // xmlhttp.setRequestHeader("Connection", "close");
        xmlhttp.send(params);
   },
   get : function() {
        // paste here
   }
}

window.onload = function(){
    var myform = document.getElementById('odm_tutorial');
    myform.onsubmit = function(){
        var elements = myform.getElementsByTagName('input');
        var elementsClass = document.getElementsByClassName('input-error');
        
        ajax.post(myform.getAttribute('action'), function(json){
            obj = JSON.parse(json);
            
                for(var i=0; i < elements.length; i++){
                    e = document.createElement('div');
                    e.className = 'input-error';
                    var text = elements[i].name;
                    if(elements[i].type == 'checkbox'){
                            if(typeof obj.errors !== 'undefined'){
                                if(typeof elements[i].parentNode.childNodes[5] !== 'undefined'){
                                    elements[i].parentNode.childNodes[5].innerHTML = obj.errors[text];
                                } else {
                                    e.innerHTML = obj.errors[text];
                                    elements[i].parentNode.appendChild(e);
                                }
                            } else {
                                if(typeof elements[i].parentNode.childNodes[5] !== 'undefined'){
                                    elements[i].parentNode.childNodes[5].remove(); 
                                }
                            }
                    } else {
                        if (elements[i].type != 'submit') { 
                            if(typeof obj.errors !== 'undefined'){
                                if(typeof elements[i].parentNode.childNodes[3] !== 'undefined'){
                                    elements[i].parentNode.childNodes[3].innerHTML = obj.errors[text];
                                } else {
                                    e.innerHTML = obj.errors[text];
                                    elements[i].parentNode.appendChild(e);
                                }
                            } else {
                                if(typeof elements[i].parentNode.childNodes[3] !== 'undefined'){
                                    elements[i].parentNode.childNodes[3].remove(); 
                                }
                            }
                        }
                    }
                }

            console.log(obj.errors);
        }, 
        new FormData(myform)); 
        
        return false;  // Do not do form submit;
    }
}
</script>
    </head>
    <body>
        <header>
            <?php echo Url::anchor('/', Html::img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Odm Ajax Tutorial</h1>
        <section>
            <?php echo Form::open('tutorials/hello_ajax/dopost.json', array('method' => 'POST', 'id' => 'odm_tutorial')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo Form::label('Email') ?></td>
                        <td>
                            <?php echo Form::input('email', Form::setValue('email'), " id='email' ");?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Form::label('Password') ?></td>
                        <td>
                            <?php echo Form::password('password', '', " id='password' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Form::label('Confirm') ?></td>
                        <td>
                            <?php echo Form::password('confirm_password', '', " id='confirm' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo Form::checkbox('agreement', 1, Form::setValue('agreement'), " id='agreement' ") ?>

                            <label for="agreement">I agree terms and conditions.</label>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo Form::submit('dopost', 'Do Post', ' ') ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>

            <?php echo Form::close(); ?>
            
        </section>
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
</html>