<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="@assets@/css/welcome.css" rel="stylesheet" type="text/css" />
        <title>Scheme Tutorial</title>
    </head>

    <body>

<header>
    <?php echo $this->url->anchor('/', '<img src="@assets@/images/logo.png">') ?>
</header>

<h1>Hello Scheme World</h1>

<p>This my first content using schemes.</p>

<section>

<p><b>Layers</b> call view and sidebar controllers then assign outputs to their variables.</p>

<pre>
'default' => function () {
    $this->assign('header',  '@public.views/header');
    $this->assign('sidebar', '@public.views/sidebar');
    $this->assign('footer',  $this->template('footer'));
},
</pre>

<p></p>

<p><b>"Default"</b> config defined in your main config file and <b>view class</b> defined in your <kbd>components.php</kbd> file.</p>

<p></p>

<p><kbd>$this->getScheme();</kbd> method calls <b>welcome</b> scheme function then assign them to <b>hello_scheme</b> file.</p>

<pre>$this->view->load('hello_scheme', function() {
        $this->assign('name', 'Obullo');
        $this->assign('title', 'Hello Scheme World !');
        $this->getScheme('welcome');
});</pre>
</section>


<?php echo $footer ?>

<section>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</section>

</body>
</html>