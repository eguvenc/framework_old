<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Layered Vc Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        <h1>Layered Vc Tutorial</h1>
<pre>
$c->load('public');
$a = $this->public->get('tutorials/hello_dummy/1/2/3');
$b = $this->public->get('welcome/welcome_dummy/4/5/6');
$c = $this->public->get('tutorials/hello_dummy/7/8/9');</pre>

        <section><p>&nbsp;</p></section>

        <section>
            <?php echo $a ?>
            <?php echo $b ?>
            <?php echo $c ?>
        </section>

        <?php echo $footer ?>

        <section>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </section>

    </body>
    
</html>