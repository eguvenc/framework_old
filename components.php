<?php
/*
|--------------------------------------------------------------------------
| COMPONENTS
|--------------------------------------------------------------------------
| This file specifies the your application components.
*/
/*
|--------------------------------------------------------------------------
| Db
|--------------------------------------------------------------------------
*/
$c['db'] = function () use ($c) {
    return new Obullo\Database\Pdo\Mysql($c, $c->load('config')['database']['db']);
};
/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/
$c['session'] = function () use ($c) {
    return new Obullo\Session\Session(
        $c, 
        $c->load('config')['session'],
        new Obullo\Session\Handler\Cache($c, $c->load('config')['session'])
    );
};
/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/
$c['form'] = function () use ($c) {
    return new Obullo\Form\Form(
        $c,
        array(
            NOTICE_MESSAGE => '<div class="{class}">{icon}{message}</div>',
            NOTICE_ERROR   => array('class' => 'alert alert-danger', 'icon' => '<span class="glyphicon glyphicon-remove-sign"></span>'),
            NOTICE_SUCCESS => array('class' => 'alert alert-success', 'icon' => '<span class="glyphicon glyphicon-ok-sign"></span> '),
            NOTICE_WARNING => array('class' => 'alert alert-warning', 'icon' => '<span class="glyphicon glyphicon-exclamation-sign"></span>'),
            NOTICE_INFO    => array('class' => 'alert alert-info', 'icon' => '<span class="glyphicon glyphicon-info-sign"></span> '),
        )
    );
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
    return new Obullo\Translation\Translator($c, $c->load('config')->load('translator'));
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
| Lets you define "schemes" to extend views without hacking
| the view function.
*/
$c['view'] = function () use ($c) {
    $config['schemes'] = array(
        'default' => function () {
            $this->assign('header', '@layer.views/header');
            $this->assign('sidebar', '@layer.views/sidebar');
            $this->assign('footer', $this->template('footer'));
        },
        'welcome' => function () {
            $this->assign('footer', $this->template('footer'));
        },
    );
    return new Obullo\View\View($c, $config);
};
/*
|--------------------------------------------------------------------------
| Layered Vc Public Request
|--------------------------------------------------------------------------
*/
$c['layer'] = function () use ($c) { 
    return new Obullo\Layer\Request($c, $c->load('config')['layers']);
};
/*
|--------------------------------------------------------------------------
| Uri
|--------------------------------------------------------------------------
*/
$c['uri'] = function () use ($c) {
    return new Obullo\Uri\Uri($c, $c->load('config')['uri']);
};
/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/
$c['router'] = function () use ($c) { 
    return new Obullo\Router\Router($c, $c->load('config')['router']);
};
/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/
require OBULLO_ROUTES;
/*
|--------------------------------------------------------------------------
| Initialize Routes
|--------------------------------------------------------------------------
*/
$c['router']->init();

/* End of file components.php */
/* Location: .components.php */