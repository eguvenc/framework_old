<html>
    <head>
        <title><?php echo $title ?></title>
        <?php echo $this->html->css('style.css') ?>
        <meta charset="utf-8">
    </head>

<body>
        <?php echo $header ?>

        <div id="clear"></div>
        <div id="containerbox">
             
            <div id="content x" class="x mt">
                    <div id="navigation">
                        <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Signup</b>
                    </div>

                    <h1>Signup</h1>
                    <div id="container">
                    
                    <section><?php echo $this->form->getMessage() ?></section>
                    <section><?php echo $this->form->getNotice() ?></section>

                        <p>Please fill out the following form with your login credentials: </p>

                        <?php echo $this->form->open('membership/signup', array('method' => 'POST')) ?>
                            <table width="100%">

                                <tr>
                                    <td style="width:15%;"><?php echo $this->form->label('Username') ?></td>
                                    <td><?php 
                                        echo $this->form->error('user_username');
                                        echo $this->form->input('user_username', $this->form->setValue('user_username'), " id='user_username' ");
                                        ?></td>
                                </tr>
                                <tr>
                                    <td style="width:15%;"><?php echo $this->form->label('Email') ?></td>
                                    <td><?php 
                                        echo $this->form->error('user_email');
                                        echo $this->form->input('user_email', $this->form->setValue('user_email'), " id='user_email' ");
                                        ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $this->form->label('Password') ?></td>
                                    <td><?php 
                                        echo $this->form->error('user_password');
                                        echo $this->form->password('user_password', '', " id='user_password' ");
                                        ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $this->form->label('Confirm Password') ?></td>
                                    <td><?php 
                                        echo $this->form->error('confirm_password');
                                        echo $this->form->password('confirm_password', '', " id='confirm_password' ");
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

                    </div>
                
                <div id="clear"></div><div id="blockbottom"> </div>
            </div>

            <?php echo $footer ?>

        </div>
</body>

</html>