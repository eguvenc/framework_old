<?php

namespace Examples;

use Event\Login\Attempt;

Class Login extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['form'];
        $this->c['user'];
        $this->c['view'];
        $this->c['flash'];
        $this->c['request'];
        $this->c['event']->subscribe(new Attempt($this->c));   // Listen user events
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->c['validator'];

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
                    $this->url->redirect('examples/restricted');

                } else {

                    $this->validator->setErrors($result->getArray());
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