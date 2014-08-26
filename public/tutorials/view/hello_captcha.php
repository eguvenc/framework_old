<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo $this->html->css('welcome.css') ?>
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
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        <h1>Hello Captcha</h1>
        <section><?php echo $this->form->message() ?></section>

        <section>
                <form action="/tutorials/hello_captcha" method="post" />
                <table width="100%">
                    <tr>
                        <td style="width:20%;">Email</td>
                        <td><?php echo $this->form->error('email') ?>
                        <input type="text" name="email" value="<?php echo $this->form->value('email') ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><?php echo $this->form->error('password') ?>
                        <input type="password" name="password" value="<?php echo $this->form->value('password') ?>" />
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
                        <td><?php echo $this->form->error('captcha_answer'); ?>
                            <input type="text" name="captcha_answer" value="<?php echo $this->form->value('captcha') ?>" />
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
            <p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p>
        </section>

    <section>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    </section>

    </body>
</html>