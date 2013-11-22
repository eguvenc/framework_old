
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
    'default' => function($file)
    {
        $this->set('content', $file);
        $this->set('footer', getInstance()->tpl('footer',false));
    },
);
</pre>

<p></p>
<p>The <kbd>$this->getScheme();</kbd> method load the <b>default</b> scheme and fetches the <b>hello_scheme</b> view using <kbd>$c->view();</kbd> function.</p>

<pre>$c->tpl('default', function() use($c) {
        $this->set('name', 'Obullo');
        $this->set('title', 'Hello Scheme World !');
        $this->getScheme($c->view('hello_scheme', false));
});</pre>
</section>