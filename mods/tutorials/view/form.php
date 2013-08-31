<?php
    $user = vi\getArray('user');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo html\css('welcome.css') ?>
        <title>ODM Tutorial</title>
    </head>

    <body>
        <header>
                <a href="http://www.obullo.com/"><?php echo html\img('logo.png', ' alt="Obullo" ') ?></a>
        </header>
        
        <h1>ODM Tutorial</h1>
        <h2><?php echo url\anchor('/tutorials/form_ajax', 'Ajax Example') ?></h2>
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
            <?php echo form\open('/tutorials/form/post', array('method' => 'POST')) ?>
            
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
                        <td><?php echo form\submit('do_post', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">Test Results</td>
                    </tr>    
                    <?php if($user) { ?>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>form\error('user_email')</td>
                            <td><pre><?php echo form\error('user_email') ?></pre></td>
                        </tr>
                        <tr>
                            <td>validationErrors()</td>
                            <td><pre><?php echo form\validationErrors(' | ', ' | '); ?></pre></td>
                        </tr>
                        <tr>
                            <td>print_r($user->errors())</td>
                            <td><pre><?php print_r($user->errors()) ?></pre></td>
                        </tr>

                        <tr>
                            <td>$user->errors('user_email')</td>
                            <td><pre><?php echo $user->errors('user_email'); ?></pre></td>
                        </tr>

                        <tr>
                            <td>print_r($user->values())</td>
                            <td><pre><?php print_r($user->values()) ?></pre></td>
                        </tr>

                        <tr>
                            <td>$user->values('user_email')</td>
                            <td><pre><?php echo $user->values('user_email') ?></pre></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>
            
            <?php echo form\close(); ?>
        </section> 
    </body>
    
</html>