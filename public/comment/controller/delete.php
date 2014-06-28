<?php

/**
 * $c delete
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('form');
        $c->load('private');
    }
);

$app->func(
    'index',
    function ($id) {
        
        $r = $this->private->delete('private/comments/delete/', array('id' => $id));

        $this->sess->setFlash(array('notice' => $r['message'], 'status' => $r['success']));       // set flash notice
        $this->url->redirect('/comment/display');
    }
);
