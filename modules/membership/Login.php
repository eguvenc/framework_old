<?php

namespace Membership;

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
    }

    /**
     * Index
     *
     * @event->subscribe('Event\Login\Attempt');
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

                $result = $this->user->login->attempt(
                    array(
                        $this->user->config['db.identifier'] => $this->validator->getValue('email'), 
                        $this->user->config['db.password']   => $this->validator->getValue('password')
                    ),
                    $this->request->post('rememberMe')
                );

                if ($result->isValid()) {

                    // $this->user->identity->makeTemporary();
                    // $this->user->identity->makePermanent();

                    $this->flash->success('You have authenticated successfully.');
                    $this->url->redirect('membership/restricted');

                } else {

                    $this->validator->setErrors($result->getArray());
                    $this->form->setErrors($this->validator);
                }
            }
        }

        $this->view->load('login');
    }
}