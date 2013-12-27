<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo $this->html->css('welcome.css') ?>

        <title><?php echo $title ?></title>
    </head>

<body>
<header>
    <?php
     echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" '))
     ?>
</header>

<h1>Hello Scheme World</h1>

<section>
	<p>This my first content using schemes.</p>
	<p>This page use the scheme function located in your <kbd>app/config/scheme.php</kbd> file.</p>

<pre>$scheme = array(
    'default' => function()
    {
        $this->set('footer', getInstance()->tpl('footer',false));
    },
);
</pre>

<p></p>
<p>The <kbd>$this->getScheme();</kbd> method load the <b>default</b> scheme and fetches the <b>hello_scheme</b> view using <kbd>$c->view();</kbd> function.</p>

<pre>$c->view('hello_scheme', function() use($c) {

        $this->set('name', 'Obullo');
        $this->set('title', 'Hello Scheme World !');

        $this->getScheme('default');
});</pre>
</section>

<?php echo $footer ?>

<section>
    <p>&nbsp;</p>
</section>

</body>
</html>