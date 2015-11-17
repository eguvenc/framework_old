<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <link href="<?php echo $this->url->asset('css/navigation.css') ?>" rel="stylesheet" type="text/css" />
        <title><?php echo $title ?></title>
    </head>
    <body>

        <header>
            <?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?>
            <?php echo $header ?>
        </header>

        <h1>Welcome</h1>
        <p>Congratulations! Your Obullo is running. If this is your first time using Obullo, start with Tutorials.</p>

        <section>
            <h2>Getting  Started with Tutorials</h2>
            <ol>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloWorld', 'Hello World') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloLayout', 'Hello Layout') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloForm', 'Hello Form') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloElement', 'Hello Element') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloAjax', 'Hello Ajax') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloTask', 'Hello Task') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloLayers', 'Hello Layers') ?></li>
                <li><?php echo $this->url->anchor('/widgets/tutorials/helloCaptcha', 'Hello Captcha') ?></li>
            </ol>
        </section>

        <section>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </section>
        <?php echo $footer ?>
    </body>
</html>