<?php

/**
 * $c update
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('form');
        $c->load('hvc');
    }
);

$c->func(
    'index.Private_User',
    function ($id, $status = 'approve') {

        $r = $this->private->put('comment/update/'.$id.'/'.$status);

        $this->form->setNotice($r['message'], $r['success']);  // set flash notice
        $this->url->redirect('/comment/display');
    }
);