<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="<?php echo $this->url->asset('css/welcome.css') ?>" rel="stylesheet" type="text/css" />
        <title>Hello Element</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?>
        </header>
        
        <h1>Hello Element</h1>

        <section><?php echo $this->form->getMessage() ?></section>
        <section><?php echo $this->form->getValidationErrors() ?></section>

        <section>
            <?php echo $this->element->form('/widgets/tutorials/helloElement') ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;">Email</td>
                        <td><?php echo $this->form->getError('email'); ?>
                        <?php echo $this->element->input('email', $this->form->getValue('email')) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><?php echo $this->form->getError('password'); ?>
                        <?php echo $this->element->password('password') ?></td>
                    </tr>
                    <tr>
                        <td>Confirm</td>
                        <td><?php echo $this->form->getError('confirm_password'); ?>
                        <?php echo $this->element->password('confirm_password') ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $this->form->getError('agreement'); ?>
                        <?php echo $this->element->checkbox('agreement', 1, $this->form->getValue('agreement'), ' id="agreement" ') ?>
                        <?php echo $this->element->label('I agree terms and conditions', 'agreement') ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <?php echo $this->element->submit('dopost', 'DoPost') ?>
                        </td>
                    </tr>
                </table>

            <?php echo $this->element->formClose() ?>

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