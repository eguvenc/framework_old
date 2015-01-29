<?php

namespace Examples;

use Event\User;

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
        $this->c->load('user');
        $this->c->load('view');
        $this->c->load('flash/session as flash');
        $this->c->load('password');
        $this->c->load('event')->subscribe(new User($this->c));   // Listen user events
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->c['request']->isPost()) {

            $this->c->load('validator'); // load validator

            $this->validator->setRules('email', 'Email', 'required|email|trim');
            $this->validator->setRules('password', 'Password', 'required|min(6)|trim');

            if (  ! $this->validator->isValid()) {
                $this->form->setErrors($this->validator);

            } else {

                // $this->user->login->enableVerification();

                $result = $this->user->login->attempt(
                    array(
                        $this->c['auth.params']['db.identifier'] => $this->validator->getValue('email'), 
                        $this->c['auth.params']['db.password']   => $this->validator->getValue('password')
                    ),
                    $this->request->post('rememberMe')
                );

                if ($result->isValid()) {

                    $this->flash->success('You have authenticated successfully.');
                    $this->url->redirect('examples/login');

                } else {

                    $this->validator->setError($result->getArray());
                    $this->form->setErrors($this->validator);
                }
            }
        }

        $this->view->load(
            'login',
            [
                'footer' => $this->view->template('footer')
            ]
        );
    }
}

/* End of file logout.php */
/* Location: .controllers/examples/login.php */