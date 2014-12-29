<?php

namespace Examples;

use Auth\Credentials,
    Auth\Identities\GenericUser,
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
        $this->c->load('request');
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
        if ($this->request->isPost()) {

            // $this->user->login->enableVerification();
            
            $result = $this->user->login->attempt(
                array(
                    Credentials::IDENTIFIER => filter_var($this->request->post('email'), FILTER_VALIDATE_EMAIL),  // No need to use validator
                    Credentials::PASSWORD => $this->request->post('password')
                ),
                $this->request->post('rememberMe')
            );

            if ($result->isValid()) {
                
                print_r($result->getArray());

                $this->flash->success('You have authenticated successfully.');
                $this->url->redirect('examples/login');
                
            } else {
            
                $this->form->setErrors($result->getArray());
            }
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