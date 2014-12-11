<?php
/*
|--------------------------------------------------------------------------
| Components
|--------------------------------------------------------------------------
| Specifies the your application components which they available by default.
*/
/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/
$c['session'] = function () use ($c) {
    return new Obullo\Session\Session($c, $c['config']['session']);
};
/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
$c['form'] = function () use ($c) {
    return new Obullo\Form\Form($c, $c['config']->load('form'));
};
/*
|--------------------------------------------------------------------------
| Event
|--------------------------------------------------------------------------
*/
$c['event'] = function () use ($c) {
    return new Obullo\Event\Event($c);
};
/*
|--------------------------------------------------------------------------
| Exception
|--------------------------------------------------------------------------
*/
$c['exception'] = function () use ($c) {
    return new Obullo\Error\Exception($c);
};
/*
|--------------------------------------------------------------------------
| Translator
|--------------------------------------------------------------------------
*/
$c['translator'] = function () use ($c) { 
    return new Obullo\Translation\Translator($c, $c['config']->load('translator'));
};
/*
|--------------------------------------------------------------------------
| Request
|--------------------------------------------------------------------------
*/
$c['request'] = function () use ($c) { 
    return new Obullo\Http\Request($c);
};
/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/
$c['response'] = function () use ($c) { 
    return new Obullo\Http\Response($c);
};
/*
|--------------------------------------------------------------------------
| Input Get
|--------------------------------------------------------------------------
*/
$c['get'] = function () use ($c) { 
    return new Obullo\Http\Get($c);
};
/*
|--------------------------------------------------------------------------
| Input Post
|--------------------------------------------------------------------------
*/
$c['post'] = function () use ($c) { 
    return new Obullo\Http\Post($c);
};
/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
*/
$c['view'] = function () use ($c) {
    return new Obullo\View\View($c, $c['config']['view']['layouts']);
};
/*
|--------------------------------------------------------------------------
| Layers
|--------------------------------------------------------------------------
*/
$c['layer'] = function () use ($c) { 
    return new Obullo\Layer\Request($c, $c['config']['layer']);
};
/*
|--------------------------------------------------------------------------
| Uri
|--------------------------------------------------------------------------
*/
$c['uri'] = function () use ($c) {
    return new Obullo\Uri\Uri($c, $c['config']['uri']);
};
/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/
$c['router'] = function () use ($c) { 
    return new Obullo\Router\Router($c, $c['config']['router']);
};


/* End of file components.php */
/* Location: .components.php */