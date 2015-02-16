<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="@ASSETS/css/welcome.css" rel="stylesheet" type="text/css" />
        <title>Hello Captcha</title>

<script type="text/javascript">
var ajax = {
    get : function(url, closure, params){
        var xmlhttp;
        if (window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
        }
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
    post : function() {
        // paste here
    }
}
</script>
    </head>
    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="@ASSETS/images/logo.png">') ?>
        </header>
        <h1>Hello Captcha</h1>
        <section><?php echo $this->form->getMessage() ?></section>

        <section>
                <form action="/widgets/tutorials/hello_captcha" method="post" />
                <table width="100%">
                    <tr>
                        <td style="width:20%;">Email</td>
                        <td><?php echo $this->form->getError('email') ?>
                        <input type="text" name="email" value="<?php echo $this->form->getValue('email') ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><?php echo $this->form->getError('password') ?>
                        <input type="password" name="password" value="<?php echo $this->form->getValue('password') ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <img src="/index.php/widgets/captcha/create" alt="Security Image" id="captcha" />
                        <a href="javascript:void(0);" onclick="document.getElementById('captcha').src='/index.php/widgets/captcha/create/'+Math.random();" id="image">Refresh</a> 
                        </td>
                    </tr>
                    <tr>
                        <td>Captcha Code</td>
                        <td><?php echo $this->form->getError('captcha_answer'); ?>
                            <input type="text" name="captcha_answer" value="<?php echo $this->form->getValue('captcha') ?>" />
                            </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="dopost" value="DoPost" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                </form>
        </section> 

    <section>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    </section>

    </body>
</html>