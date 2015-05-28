<?php

namespace Membership;

class Login extends \Controller
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
    }

    /**
     * Index
     *
     * @event->when("post")->subscribe('Event\Login\Attempt');
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

                $authResult = $this->user->login->attempt(
                    [
                        $this->user['db.identifier'] => $this->validator->getValue('email'), 
                        $this->user['db.password']   => $this->validator->getValue('password')
                    ],
                    $this->request->post('rememberMe')
                );

                if ($authResult->isValid()) {
                    $this->flash->success('You have authenticated successfully.')->url->redirect('membership/restricted');
                } else {
                    $this->form->setResults($authResult->getArray());
                }
            }
        }
        $this->view->load('login');
    }
}