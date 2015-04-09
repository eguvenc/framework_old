<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <link href="<?php echo $this->url->asset('css/welcome.css') ?>" rel="stylesheet" type="text/css" />
        <title><?php echo $title ?></title>
    </head>
    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?>
            <?php echo $header ?>
        </header>

        <h1>Welcome</h1>
        <p>
            Congratulations! Your Obullo is running. If this is your first time using Obullo, start with Tutorials.
        </p>

        <section>
            <h2>Getting  Started with Tutorials</h2>

            <ol>
                <li><?php echo $this->url->anchor('/widgets/tutorials/hello_world', 'Hello World') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/hello_layout', 'Hello Layout') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/hello_form', 'Hello Form') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/hello_ajax', 'Hello Ajax') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/hello_task', 'Hello Task') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/hello_layers', 'Hello Layers') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/hello_captcha', 'Hello Captcha') ?></li>
            </ol>

        </section>
        
        <section>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </section>

        <?php echo $footer ?>

    </body>
</html>