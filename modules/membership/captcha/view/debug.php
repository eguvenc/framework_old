<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="<?php echo $this->url->asset('css/welcome.css') ?>" rel="stylesheet" type="text/css" />
        <title>Captcha Debug</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?>
        </header>

        <h1>Captcha Debug</h1>

        <section>

            <?php print($fonts); ?>
              
        </section>

        <?php echo $footer ?>
    </body>
    
</html>