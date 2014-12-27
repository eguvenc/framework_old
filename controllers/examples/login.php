<?php

namespace Examples;

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
        $this->c->load('event')->subscribe(new \Event\User($this->c));   // Listen user events
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        // $this->user->login->authenticateVerifiedIdentity();
        
        var_dump($this->user->identity->guest());
        var_dump($this->user->identity->check());

        if ($this->post['dopost']) {

            $this->c->load('validator');

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
}

/* End of file logout.php */
/* Location: .controllers/examples/login.php */