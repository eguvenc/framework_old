<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="<?php echo $this->url->asset('css/welcome.css') ?>" rel="stylesheet" type="text/css" />
        <title>Hello Form</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?>
        </header>
        
        <h1>Hello Form</h1>

        <section><?php echo $this->form->getMessage() ?></section>
        <section><?php echo $this->form->getValidationErrors() ?></section>

        <section>
            <form action="/widgets/tutorials/helloCsrf" method="POST">

                <input type="hidden" name="<?php echo $this->c['csrf']->getTokenName() ?>" value="<?php echo $this->c['csrf']->getToken(); ?>" />

                <table width="100%">
                    <tr>
                        <td style="width:20%;">Email</td>
                        <td><?php echo $this->form->getError('email'); ?>
                        <input type="text" name="email" value="<?php echo $this->form->getValue('email') ?>" />
                        </td>
                    </tr>

                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" name="dopost" value="DoPost" />
                        </td>
                    </tr>
                    </table>
                </form>

                <h2>Token<h2>

                <code><?php echo $this->c['csrf']->getToken() ?></code>


                <h2>Test Results</h2>

                <section>
                    <h3>$this->form->outputArray()</h3>
                    <pre><?php print_r($this->form->outputArray()); ?>
                    </pre>

                    <h3>$this->form->getError('email')</h3>
                    <pre><?php echo $this->form->getError('email') ?></pre>
                </section> 
        </section> 

    </body>
    
</html>