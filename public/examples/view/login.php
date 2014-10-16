<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="@assets@/css/welcome.css" rel="stylesheet" type="text/css" />
        <title>Login</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="@assets@/images/logo.png">') ?>
        </header>
        
        <h1>Login Example</h1>

        <section><?php echo $this->form->message() ?></section>

        <section>
            <form action="/examples/login" method="POST">
                <table width="100%">
                    <tr>
                        <td style="width:20%;">Email</td>
                        <td><?php echo $this->form->error('email'); ?>
                        <input type="text" name="email" value="<?php echo $this->form->value('email') ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><?php echo $this->form->error('password'); ?>
                        <input type="password" name="password" value="" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $this->form->error('rememberMe'); ?>
                        <input type="checkbox" name="rememberMe" value="1"  id="rememberMe"><label for="rememberMe"> Remember Me</label></td>
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

                <h2>Test Results</h2>

                <section>
                    <h3>$this->form->outputArray()</h3>
                    <pre><?php print_r($this->form->outputArray()); ?>
                    </pre>

                    <h3>$this->form->error('email')</h3>
                    <pre><?php echo $this->form->error('email') ?></pre>
                </section>    

        </section> 

        <?php echo $footer ?>
    </body>
    
</html>