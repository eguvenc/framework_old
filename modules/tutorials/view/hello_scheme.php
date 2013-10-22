
<?php $this->set('head', Html::css('welcome.css')) ?>

<header>
    <?php echo Url::anchor('/', Html::img('logo.png', ' alt="Obullo" ')) ?>
</header>

<h1>Hello Scheme World</h1>

<section>
	<p>This my first content using schemes.</p>
	<p>This page use the scheme function located in your <kbd>app/config/scheme.php</kbd> file.</p>

<pre>$scheme['general'] = function($filename){
	$this->set('header', tpl('header',false));
	$this->set('content', view($filename, false));
	$this->set('footer', tpl('footer',false));	
};</pre>

<p></p>
<p>The scheme method load the general scheme and fetches the hello_scheme view using <b>view();</b> function.</p>

<pre>tpl('default', function(){
	$this->set('title', 'Hello Scheme');
	$this->set('name', 'Obullo');
	$this->scheme('general', 'hello_scheme');
});</pre>
</section>