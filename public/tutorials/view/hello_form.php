<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Hello Form</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hello Form</h1>

        <section><?php echo $this->flash->get('notice') ?></section>
        <section><?php echo $this->form->message() ?></section>
        <section><?php echo $errorString ?></section>

        <section>

            <form action="/tutorials/hello_form" method="POST">
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
                        <td>Confirm</td>
                        <td><?php echo $this->form->error('confirm_password'); ?>
                        <input type="password" name="confirm_password" value="" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $this->form->error('agreement'); ?>
                        <input type="checkbox" name="agreement" value="1"  id="agreement"><label for="agreement"> I agree terms and conditions</label></td>
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

                    <h3>print_r($errors)</h3>
                    <pre><?php print_r($errors); ?>
                    </pre>

                    <h3>$this->form->output()</h3>
                    <pre><?php print_r($this->form->outputArray()); ?>
                    </pre>

                    <h3>$this->form->error('email')</h3>
                    <pre><?php echo $this->form->error('email') ?></pre>

                    <h3>echo $errorString</h3>
                    <pre><?php echo $errorString ?></pre>
                </section>    

        </section> 

        <?php echo $footer ?>
    </body>
    
</html>