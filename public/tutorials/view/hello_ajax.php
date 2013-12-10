<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo $this->html->css('welcome.css') ?>

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
        xmlhttp.send(params);
   },
   get : function() {
        // paste here
   }
}

window.onload = function(){
    var myform = document.getElementById('odm_tutorial');  // Form ID
    myform.onsubmit = function(){
        var elements = myform.getElementsByTagName('input');
        var elementsClass = document.getElementsByClassName('_inputError');
        
        ajax.post(myform.getAttribute('action'), function(json){
            var obj = JSON.parse(json);
                for(var i=0; i < elements.length; i++){
                    e = document.createElement('div');
                    e.className = '_inputError';
                    if(elements[i].type == 'checkbox'){
                        parseNode(obj, elements[i], 5);
                    } else if(elements[i].type != 'submit') {
                        parseNode(obj, elements[i], 3);
                    }
                }
            },
            new FormData(myform)
            );
        return false;  // Do not do form submit;
    }
}

function parseNode(obj, element, child){
    var name = element.name;
    if(typeof obj.errors[name] !== 'undefined'){
        if(typeof element.parentNode.childNodes[child] !== 'undefined'){
            element.parentNode.childNodes[child].innerHTML = obj.errors[name];
        } else {
            e.innerHTML = obj.errors[name];
            element.parentNode.appendChild(e);
        }
    } else {
        if(typeof element.parentNode.childNodes[child] !== 'undefined'){
            element.parentNode.childNodes[child].remove(); 
        }
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
            <?php
            echo $this->form->open('tutorials/hello_ajax/dopost.json', array('method' => 'POST', 'id' => 'odm_tutorial')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo $this->form->label('Email') ?></td>
                        <td>
                            <?php echo $this->form->input('email', $this->form->setValue('email'), " id='email' ");?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Password') ?></td>
                        <td>
                            <?php echo $this->form->password('password', '', " id='password' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Confirm') ?></td>
                        <td>
                            <?php echo $this->form->password('confirm_password', '', " id='confirm' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo $this->form->checkbox('agreement', 1, $this->form->setValue('agreement'), " id='agreement' ") ?>

                            <label for="agreement">I agree terms and conditions.</label>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo $this->form->submit('dopost', 'Do Post', ' ') ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>

            <?php echo $this->form->close(); ?>
            
        </section>
        <section>
            <p>&nbsp;</p>
        </section>
    </body>
</html>