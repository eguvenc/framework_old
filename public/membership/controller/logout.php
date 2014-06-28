<?php

/**
 * $c logout
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
    }
);

$app->func(
    'index',
    function () {
        $this->auth->clearIdentity();  // remove auth data
        $this->url->redirect('membership/login');  // redirect user to logout screen
    }
);