<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="@ASSETS/css/welcome.css" rel="stylesheet" type="text/css" />
        <title>Layers Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', '<img src="@ASSETS/images/logo.png">') ?>
        </header>
        <h1>Layer Tutorials</h1>
<pre>
$a = $this->c['layer']->get('widgets/tutorials/hello_dummy/1/2/3');
$b = $this->c['layer']->get('welcome/dummy/4/5/6');
$c = $this->c['layer']->get('widgets/tutorials/hello_dummy/7/8/9');</pre>

        <section><p>&nbsp;</p></section>

        <section>
            <?php echo $a ?>
            <?php echo $b ?>
            <?php echo $c ?>
        </section>

        <?php echo $this->c['view']->template('footer'); ?>

        <section>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </section>

    </body>
    
</html>