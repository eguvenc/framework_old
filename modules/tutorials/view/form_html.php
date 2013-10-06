<?php
    $user = getVar('user');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo Html::css('welcome.css') ?>
        <title>Odm Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo Url::anchor('/', Html::img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Odm Tutorial</h1>
        <h2><?php echo Url::anchor('tutorials/form_ajax', 'Ajax Tutorial') ?></h2>
        <section><?php echo Form::message($user, '', '<div class="notification error">', '</div>') ?></section>

        <section>
            <?php
            if(Sess::getFlash('notice') != '')
            {
                echo Sess::getFlash('notice', '<div class="notification success">', '</div>');   
            }
            ?>
        </section>
        
        <section>
            <?php echo Form::open('tutorials/form_html/dopost', array('method' => 'POST')) ?>
                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo Form::label('Email') ?></td>
                        <td>
                        <?php echo Form::error('user_email', '<div class="input-error">', '</div>'); ?>
                        <?php echo Form::input('user_email', Form::setValue('user_email'), " id='email' ");?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Form::label('Password') ?></td>
                        <td>
                        <?php echo Form::error('user_password', '<div class="input-error">', '</div>'); ?>
                        <?php echo Form::password('user_password', '', " id='password' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Form::label('Confirm') ?></td>
                        <td>
                        <?php echo Form::error('user_confirm_password', '<div class="input-error">', '</div>') ?>
                        <?php echo Form::password('user_confirm_password', '', " id='confirm' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <?php echo Form::error('agreement', '<div class="input-error">', '</div>') ?>    
                        <?php echo Form::checkbox('agreement', 1, Get::post('agreement'), " id='agreement' ") ?><label for="agreement">I agree terms and conditions.</label></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo Form::submit('do_post', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                    
                    <h2>Test Results</h2>
                    <?php if($user) { ?>

                        <section>
                            <h3>Form::error('user_email')</h3>
                            <pre><?php echo Form::error('user_email') ?></pre>

                            <h3>Form::validationErrors()</h3>
                            <pre><?php echo Form::validationErrors(' | ', ' | '); ?></pre>

                            <h3>print_r($user->errors())</h3>
                            <pre><?php print_r($user->errors()) ?></pre>

                            <h3>$user->errors('user_email')</h3>
                            <pre><?php echo $user->errors('user_email'); ?></pre>

                            <h3>print_r($user->values())</h3>
                            <pre><?php print_r($user->values()) ?></pre>

                            <h3>$user->values('user_email')</h3>
                            <pre><?php echo $user->values('user_email') ?></pre>
                        </section>

                    <?php } ?>
            <?php echo Form::close(); ?>
        </section> 
        
        <section>
            <p>Total memory usage {memory_usage}.</p>
        </section>
    </body>
    
</html>