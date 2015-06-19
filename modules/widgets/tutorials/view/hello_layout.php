<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="<?php echo $this->url->asset('css/welcome.css') ?>" rel="stylesheet" type="text/css" />
        <title>Scheme Tutorial</title>
    </head>

<body>
<header>
    <?php echo $this->url->anchor('/', '<img src="'.$this->url->asset('images/logo.png').'">') ?>
</header>

<h1>Hello Layout World</h1>

<section>

<p>Put your layout to <kbd>app/classes/Views/Layout</kbd> page.</p>

<pre>
namespace View\Layout;

Trait Base
{
    /**
     * Setup layout & assign view variables
     * 
     * @return void
     */
    public function extend()
    {
        $this->view->assign(
            [
                'header' => $this->layer->get('views/header'),
                'footer' => $this->layer->get('views/footer')
            ]
        );
    }
}
</pre>

<p></p>

<p>Put header variable to in your views/view/header.php </p>

<pre><?php echo htmlspecialchars('<?php echo $header ?>') ?></pre>

<p></p>

<p>Put footer variable to in your views/view/footer.php </p>

<pre><?php echo htmlspecialchars('<?php echo $footer ?>') ?></pre>

</section>

<section>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</section>

</body>
</html>