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
        <h2><?php echo Url::anchor('tutorials/hello_ajax', 'Ajax Tutorial') ?></h2>

        <section>
            <?php
                if(Get::post('dopost'))
                {
                    echo $this->user->messages('errorMessage');
                }
            ?>
        </section>

        <section>
            <?php echo $this->user->getNotice() ?>
        </section>
        
        <section>

            <?php
            echo Form::open('tutorials/hello_odm/dopost', array('method' => 'POST')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo Form::label('Email') ?></td>
                        <td><?php 
                            echo Form::error('email');
                            echo Form::input('email', Form::setValue('email'), " id='email' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo Form::label('Password') ?></td>
                        <td><?php 
                            echo Form::error('password');
                            echo Form::password('password', '', " id='password' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo Form::label('Confirm') ?></td>
                        <td><?php 
                            echo Form::error('confirm_password');
                            echo Form::password('confirm_password', '', " id='confirm' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php
                            echo Form::error('agreement');
                            echo Form::checkbox('agreement', 1, Form::setValue('agreement'), " id='agreement' ");
                            echo Form::label('I agree terms and conditions', 'agreement');
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo Form::submit('dopost', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                
                <?php echo Form::close() ?>

                    <h2>Test Results</h2>
                    <?php if(isset($this->user) && is_object($this->user)) { ?>

                        <section>
                            <h3>Form::error('email')</h3>
                            <pre><?php echo Form::error('email') ?></pre>

                            <h3>print_r($this->user->output())</h3>
                            <pre><?php print_r($this->user->output()) ?></pre>

                            <h3>print_r($this->user->messages())</h3>
                            <pre><?php print_r($this->user->messages()) ?></pre>

                            <h3>print_r($this->user->errors())</h3>
                            <pre><?php print_r($this->user->errors()) ?></pre>

                            <h3>$this->user->errors('email')</h3>
                            <pre><?php echo $this->user->errors('email') ?></pre>

                            <h3>print_r($this->user->values())</h3>
                            <pre><?php print_r($this->user->values()) ?></pre>

                            <h3>$this->user->values('email')</h3>
                            <pre><?php echo $this->user->values('email') ?></pre>
                        </section>

                    <?php } ?>        

        </section> 
        
        <section>
            <p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p>
        </section>
    </body>
    
</html>