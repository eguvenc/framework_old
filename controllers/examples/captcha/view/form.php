<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="@ASSETS/css/welcome.css" rel="stylesheet" type="text/css" />
        <title>Captcha</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="@ASSETS/images/logo.png">') ?>
        </header>

        <section>
            <?php echo $this->flash->output(); ?>
        </section>

        <h1>Captcha Example</h1>

        <section style="color:red;">
            <?php
                $output = $this->form->outputArray();
                if (isset($output['errors']['message']))
                    echo $output['errors']['message'];
            ?>
        </section>
        
        <section>
            <form action="/examples/captcha/form" method="POST">
                <table width="100%">
                    <tr>
                        <td style="width:20%;">Captcha</td>
                        <td><?php echo $this->form->getError('captchaCode'); ?>
                        <!-- <input type="text" name="captcha_code" value="" /> -->
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <?php $this->captcha->printCaptcha(); ?>
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

                <h2>Debug Results</h2>

                <section>
                    <h3>$this->form->outputArray()</h3>
                    <pre><?php print_r($this->form->outputArray()); ?></pre>

                    <h3>$this->form->getError('captchaCode')</h3>
                    <pre><?php echo $this->form->getError('captchaCode') ?></pre>
                </section>
        </section>

        <?php echo $footer ?>
    </body>
    
</html>