<?php

/**
 * $c signup
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
        $c->load('private');
    }
);

$app->func(
    'index',
    function () {
        if ($this->post->get('dopost')) {  // if isset post submit
            
            $this->form->setRules('user_username', 'Username', 'required|callback_username');
            $this->form->setRules('user_email', 'Email', 'required|email');
            $this->form->setRules('user_password', 'Password', 'required|min(6)');
            $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(user_password)');
            $this->form->setRules('agreement', 'User Agreement', 'required|exact(1)');
            
            $this->form->func(
                'callback_username',
                function () {
                    $r = $this->private->post('users/getcount');
                    if ($r['results']['count'] > 0) {   // unique control
                        $this->form->setMessage('callback_username', 'The username is already taken by another member');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->form->isValid()) {  // run validation

                $r = $this->hvc->post(
                    'private/users/create',
                    array('user_password' => $this->auth->hashPassword($this->post->get('user_password'), 8))
                );

                if ($r['success']) {
                    $this->form->setNotice($r['message'], SUCCESS);
                    $this->url->redirect('/login');
                } else {
                    $this->form->setMessage($r['message']);
                }
            }
        }
        
        $this->view->load(
            'signup',
            function () {
                $this->assign('title', 'Signup to my blog');
                $this->getScheme();
            }
        );
    }
);