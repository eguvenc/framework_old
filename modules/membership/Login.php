<?php

namespace Membership;

use Obullo\Authentication\AuthConfig;

class Login extends \Controller
{
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

            $this->validator->setRules('email', 'Email', 'required|email|trim');
            $this->validator->setRules('password', 'Password', 'required|min(6)|trim');

            if (! $this->validator->isValid()) {
                $this->form->setErrors($this->validator);
            } else {

                $authResult = $this->user->login->attempt(
                    [
                        AuthConfig::get('db.identifier') => $this->validator->getValue('email'), 
                        AuthConfig::get('db.password') => $this->validator->getValue('password'),
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