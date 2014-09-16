<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Obullo</title>
    </head>
    <body>
        <header>
            <?php // echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
            <img src="/assets/images/logo.png" alt="logo" border="0" />
        </header>

        <h1>Welcome to Obullo !</h1>
        <p>
            Congratulations! Your Obullo is running. If this is your first time using Obullo, start with Tutorials.
        </p>

        <section>
            <h2>Getting  Started with Tutorials</h2>

            <ol>
                <li><?php echo $this->url->anchor('/tutorials/hello_world', 'Hello World') ?></li>
                <li><?php echo $this->url->anchor('/tutorials/hello_scheme', 'Hello Scheme') ?></li>
                <li><?php echo $this->url->anchor('/tutorials/hello_form', 'Hello Form') ?></li>
                <li><?php echo $this->url->anchor('/tutorials/hello_ajax', 'Hello Ajax') ?></li>
                <li><?php echo $this->url->anchor('/tutorials/hello_task', 'Hello Task') ?></li>
                <li><?php echo $this->url->anchor('/tutorials/hello_layers', 'Hello Layers') ?></li>
                <li><?php echo $this->url->anchor('/tutorials/hello_captcha', 'Hello Captcha') ?></li>
            </ol>

        </section>

        <section>
            <h2>Real World Application</h2>
            <ol>
                <li><?php echo $this->url->anchor('/home', 'Demo Blog') ?> * ( First run db.sql query. it is located in the root. )</li>
            </ol>
        </section>

        <?php echo $footer ?>
        
        <section>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </section>

    </body>
</html>
