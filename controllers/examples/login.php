<?php

namespace Examples;

use Auth\Constant,
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
        if ($this->request->isPost()) {

            $this->c->load('validator'); // load validator

            $this->validator->setRules('email', 'Email', 'required|email|trim');
            $this->validator->setRules('password', 'Password', 'required|min(6)|trim');

            if (  ! $this->validator->isValid()) {
                
                $this->form->setErrors($this->validator);

            } else {

                // $this->user->login->enableVerification();

                $result = $this->user->login->attempt(
                    array(
                        Constant::IDENTIFIER => $this->validator->value('email'), 
                        Constant::PASSWORD => $this->validator->value('password')
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
            function () {
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
}

/* End of file logout.php */
/* Location: .controllers/examples/login.php */