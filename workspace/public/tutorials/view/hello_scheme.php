<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Scheme Tutorial</title>
    </head>

    <body>

<header>
    <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
</header>

<h1>Hello Scheme World</h1>

<section>
	<p>This my first content using schemes.</p>
	<p>This page use <b>view class</b> scheme function that is defined in your <kbd>components.php</kbd> file.</p>

<pre>
/*
|------------------
| View Component
|------------------
$c['view'] = $c->share(
    function () use ($c) {
        $config['schemes'] = array(
            'default' => function () {
                $this->assign('header',  '@public.views/header');
                $this->assign('sidebar', '@public.views/sidebar');
                $this->assign('footer',  $this->template('footer'));
            },
            'welcome' => function () {
                $this->assign('footer', $this->template('footer'));
            },
        );
        return new Obullo\View\View($config);
    }
);
</pre>

<p></p>
<p>The <kbd>$this->getScheme();</kbd> method <b>call</b> the <b>welcome</b> scheme function then it assign templates for <b>hello_scheme</b> file.</p>

<pre>$this->view->load('hello_scheme', function() {
        $this->assign('name', 'Obullo');
        $this->assign('title', 'Hello Scheme World !');
        $this->getScheme('welcome');
});</pre>
</section>

<p>In <b>"default"</b> function <b>Layered VC</b> pattern calls view and sidebar controllers then it assign outputs to their variables.</p>

<pre>
'default' => function () {
    $this->assign('header',  '@public.views/header');
    $this->assign('sidebar', '@public.views/sidebar');
    $this->assign('footer',  $this->template('footer'));
},
</pre>


<?php echo $footer ?>

<section>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</section>

</body>
</html>