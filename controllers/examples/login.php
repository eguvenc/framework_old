<?php

namespace Examples;

use Auth\Credentials,
    Event\User;

Class Login extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('form');
        $this->c->load('view');
        $this->c->load('post');
        $this->c->load('service/user');
        $this->c->load('flash/session as flash');
        $this->c->load('event')->subscribe(new User($this->c));   // Listen user events
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        // $this->user->login->authenticateVerifiedIdentity();

        // var_dump($this->user->identity->guest());
        // var_dump($this->user->identity->check());

        if ($this->post['dopost']) {

                // $this->user->login->enableVerification();
                
                $result = $this->user->login->attempt(
                    array(
                        Credentials::IDENTIFIER => $this->post['email'], 
                        Credentials::PASSWORD => $this->post['password']
                    ),
                    $this->post['rememberMe']
                );


                // $data = $this->c->load('service/cache')->hGetAll('Auth:__permanent:Authorized:user@example.com:9fxpjde6ss');

                // var_dump($data);
                print_r($result->getArray());

                // if ($result->isValid()) {
                    
                //     print_r($result->getArray());

                //     $this->flash->success('You have authenticated successfully.');
                //     $this->url->redirect('examples/login');
                //     
                // } else {
                // 
                //     $this->form->setErrors($result->getArray());
                // }
        }

        $this->view->load(
            'login',
            function () {
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
}

/* End of file logout.php */
/* Location: .controllers/examples/login.php */