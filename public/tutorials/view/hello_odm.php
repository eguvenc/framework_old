<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Odm Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hello Odm</h1>
        <h2><?php echo $this->url->anchor('tutorials/hello_ajax', 'Ajax Tutorial') ?></h2>

        <section>
            <?php
                if($this->get->post('dopost'))
                {
                    echo $this->user->getMessage('errorMessage');
                }
            ?>
        </section>

        <section>
            <?php echo $this->user->getNotice() ?>
        </section>
        
        <section>

            <?php
            echo $this->form->open('tutorials/hello_odm/index', array('method' => 'POST')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo $this->form->label('Email') ?></td>
                        <td><?php 
                            echo $this->form->error('email');
                            echo $this->form->input('email', $this->form->setValue('email'), " id='email' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Password') ?></td>
                        <td><?php 
                            echo $this->form->error('password');
                            echo $this->form->password('password', '', " id='password' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Confirm') ?></td>
                        <td><?php 
                            echo $this->form->error('confirm_password');
                            echo $this->form->password('confirm_password', '', " id='confirm' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php
                            echo $this->form->error('agreement');
                            echo $this->form->checkbox('agreement', 1, $this->form->setValue('agreement'), " id='agreement' ");
                            echo $this->form->label('I agree terms and conditions', 'agreement');
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $this->form->submit('dopost', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                
                <?php echo $this->form->close() ?>

                    <h2>Test Results</h2>
                    <?php if(isset($this->user) AND is_object($this->user)) { ?>

                        <section>
                            <h3>print_r($this->user->output())</h3>
                            <pre><?php print_r($this->user->output()) ?></pre>

                            <h3>print_r($this->user->messages())</h3>
                            <pre><?php print_r($this->user->messages()) ?></pre>

                            <h3> echo $this->user->getMessage('errorKey')</h3>
                            <pre><?php echo $this->user->getMessage('errorKey') ?></pre>

                            <h3>print_r($this->user->errors())</h3>
                            <pre><?php print_r($this->user->errors()) ?></pre>

                            <h3>$this->user->getError('email')</h3>
                            <pre><?php echo $this->user->getError('email') ?></pre>

                            <h3>print_r($this->user->values())</h3>
                            <pre><?php print_r($this->user->values()) ?></pre>

                            <h3>$this->user->getValue('email')</h3>
                            <pre><?php echo $this->user->getValue('email') ?></pre>
                        </section>

                    <?php } ?>        

        </section> 
        
        <section>
            <p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p>
        </section>
    </body>
    
</html>