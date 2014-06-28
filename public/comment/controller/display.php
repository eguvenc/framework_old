<?php

/**
 * $c display
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('form');
        // $c->load('date_format');
        $c->load('view');
        $c->load('hvc');
    }
);

$c->func(
    'index.Private_User',
    function () {
        
        $r = $this->hvc->get('private/comments/getall');

        $this->view->get(
            'display',
            function () use ($r) {
                $this->set('title', 'Display Comments');
                $this->set('comments', $r['results']);
                $this->getScheme();
            }
        );
    }
);
