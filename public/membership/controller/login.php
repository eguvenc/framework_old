<?php

/**
 * $c login
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('form');
        $c->load('post');
        $c->load('view');
        $c->load('public');
    }
);

$app->func(
    'index',
    function () {

        if ($this->post['dopost']) {  // login button is submit ?
            
            $this->validator->setRules('email', 'Email', 'required|validEmail');
            $this->validator->setRules('password', 'Password', 'required');

            if ($this->validator->isValid()) {  // form is valid ?

                // $user = new User;
                // $user->login();

                $r = $this->public->get('private/auth.service/query');

                if ($r['success']) {      // Authorize to user

                    $this->auth->login();
                    $this->auth->setIdentity(
                        array(
                        'user_username' => $r['results']['user_username'],  // Set user data to auth container
                        'user_email'    => $r['results']['user_email'],
                        'user_id'       => $r['results']['user_id'],
                        )
                    );
                    $this->sess->setFlash('Welcome to my blog !', NOTICE_SUCCESS);
                    $this->url->redirect('/home'); // Success redirect
                }

                $this->validator->setMessage($r['message']);  // Set error message
            }

        }   // end do post.

        $this->view->load(
            'login',
            function () {
                $this->assign('title', 'Login to My blog');
                $this->getScheme();
            }
        );
    }
);
