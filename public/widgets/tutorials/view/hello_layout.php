<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="@ASSETS/css/welcome.css" rel="stylesheet" type="text/css" />
        <title>Scheme Tutorial</title>
    </head>

<body>
<header>
    <?php echo $this->url->anchor('/', '<img src="@ASSETS/images/logo.png">') ?>
</header>

<h1>Hello Layout World</h1>

<p>This my first content using layouts.</p>

<section>

<p><b>Layers</b> call view and sidebar controllers then assign outputs to their variables.</p>

<pre>
'default' => function () {
    $this->assign('header',  '@layer.views/header');
    $this->assign('sidebar', '@layer.views/sidebar');
},
</pre>

<p></p>

<p><b>"Default"</b> config defined in your <kbd>view.php</kbd> config file.</p>

<p></p>

<p><kbd>$this->layout();</kbd> method calls <b>welcome</b> layout config then assign them to <b>hello_layout</b> file.</p>

<pre>$this->view->load('hello_layout', function() {
        $this->assign('name', 'Obullo');
        $this->assign('title', 'Hello Scheme World !');
        $this->layout('welcome');
});</pre>
</section>


<?php echo $footer ?>

<section>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</section>

</body>
</html>