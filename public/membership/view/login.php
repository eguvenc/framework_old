<html>
    <head>
        <title><?php echo $title ?></title>
        <?php echo $this->html->css('style.css') ?>
        <meta charset="utf-8">
    </head>

<body>
        <?php echo $header; ?>

        <div id="clear"></div>
        <div id="containerbox">
             
            <div id="content x" class="x mt">
                    <div id="navigation">
                        <?php echo $this->url->anchor('/home', 'Home') ?> » <b> Login</b>
                    </div>

                    <h1>Login</h1>
                    
                    <div id="container">
                    <section><?php echo $this->form->getNotice();?></section>
                    <section><?php echo $this->form->getMessage();?></section>

                        <p>Please fill out the following form with your login credentials: </p>

                        <?php echo $this->form->open('membership/login/index', array('method' => 'POST')) ?>

                            <table width="100%">
                                <tr>
                                    <td style="width:15%;"><?php echo $this->form->label('Email') ?></td>
                                    <td>
                                    <?php 
                                    echo $this->form->error('email');
                                    echo $this->form->input('email', $this->form->setValue('email'), " id='email' ");?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $this->form->label('Password') ?></td>
                                    <td>
                                    <?php 
                                    echo $this->form->error('password');
                                    echo $this->form->password('password', '', " id='password' ");?></td>
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