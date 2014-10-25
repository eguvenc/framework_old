<?php

/**
 * $app login
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('form');
        $c->load('view');
        $c->load('post');
        $c->load('service/user');
        $c->load('flash/session as flash');
    }
);

$app->func(
    'index',
    function () use ($c) {

        // $c->load('service/cache');
        // $this->cache->delete('Auth:__permanent:Authorized:user@example.com:ucrmvg2j2t');

        // $this->user->login->authenticateVerifiedIdentity();
        
        var_dump($this->user->identity->isGuest());

        print_r($_SESSION);

        // $keys = $this->cache->getAllKeys('Auth:__permanent:Authorized:user@example.com:*');

        // $exp = explode(':', $keys[0]);
        // array_pop($exp);
        // $key = implode(':', $exp);
        // print_r($key);
        
        // $this->cache->delete('Auth:__permanent:Authorized:user@example.com');

        if ($this->post['dopost']) {

            $c->load('validator');

            $this->validator->setRules('email', 'Email', 'required|email|trim');
            $this->validator->setRules('password', 'Password', 'required|min(6)|trim');

            if ($this->validator->isValid()) {

                // $this->user->login->enableVerification();

                $result = $this->user->login->attempt(
                    array(
                        Auth\Credentials::IDENTIFIER => $this->validator->value('email'), 
                        Auth\Credentials::PASSWORD => $this->validator->value('password')
                    ),
                    $this->post['rememberMe']
                );

                if ($result->isValid()) {
                    $this->flash->success('You have authenticated successfully.');
                    $this->url->redirect('examples/login');
                } else {
                    $this->validator->setErrors($result->getArray());
                }
            }
            $this->form->setErrors($this->validator);
        }

        $this->view->load(
            'login',
            function () {
                $this->assign('footer', $this->template('footer'));
            }
        );

    }
);

/* End of file login.php */
/* Location: .public/examples/controller/login.php */