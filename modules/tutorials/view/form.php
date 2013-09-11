<?php
    $user = getVar('user');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo html\css('welcome.css') ?>
        <title>Odm Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo url\anchor('/', html\img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Odm Tutorial</h1>
        <h2><?php echo url\anchor('tutorials/form_ajax', 'Ajax Tutorial') ?></h2>
        <section><?php echo form\message($user, '', '<div class="notification error">', '</div>') ?></section>

        <section>
            <?php
            if(sess\getFlash('notice') != '')
            {
                echo sess\getFlash('notice', '<div class="notification success">', '</div>');   
            }
            ?>
        </section>
        
        <section>
            <?php echo form\open('tutorials/form/post', array('method' => 'POST')) ?>
                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo form\label('Email') ?></td>
                        <td>
                        <?php echo form\error('user_email', '<div class="input-error">', '</div>'); ?>
                        <?php echo form\input('user_email', form\setValue('user_email'), " id='email' ");?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo form\label('Password') ?></td>
                        <td>
                        <?php echo form\error('user_password', '<div class="input-error">', '</div>'); ?>
                        <?php echo form\password('user_password', '', " id='password' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo form\label('Confirm') ?></td>
                        <td>
                        <?php echo form\error('user_confirm_password', '<div class="input-error">', '</div>') ?>
                        <?php echo form\password('user_confirm_password', '', " id='confirm' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <?php echo form\error('agreement', '<div class="input-error">', '</div>') ?>    
                        <?php echo form\checkbox('agreement', 1, i\post('agreement'), " id='agreement' ") ?><label for="agreement">I agree terms and conditions.</label></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo form\submit('do_post', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                    
                    <h2>Test Results</h2>
                    <?php if($user) { ?>

                        <section>
                            <h3>form\error('user_email')</h3>
                            <pre><?php echo form\error('user_email') ?></pre>

                            <h3>validationErrors()</h3>
                            <pre><?php echo form\validationErrors(' | ', ' | '); ?></pre>

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
            <?php echo form\close(); ?>
        </section> 
        
        <section>
            <p>&nbsp;</p>
        </section>
    </body>
    
</html>