<?php

/**
 * $c create
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
    function ($id) {

        $r = $this->hvc->post(
            'private/posts/delete/'.$id, 
            array('user_id' => $this->auth->getIdentity('user_id'))
        );

        $this->form->setNotice($r['message'], $r['success']); // set flash notice
        $this->url->redirect('/post/manage');
    }
);