<?php

/**
 * $c manage
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('form');
        $c->load('view');
        $c->load('post');
        $c->load('hvc');
    }
);

$c->func(
    'index.Private_User',
    function () {

        $r = $this->hvc->get('private/posts/getallmanage'); // get all post data

        $this->view->get(
            'manage',
            function () use ($r) {
                $this->set('title', 'Manage Posts');
                $this->set('posts', $r['results']);
                $this->getScheme();  // hmvc yapınca request resetleniyor
            }
        );
    }
);