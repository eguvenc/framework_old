<?php

namespace Examples\Membership;

use Obullo\Http\Controller;

class Login extends Controller
{
    /**
     * Index
     *
     * @middleware->when('post')->remove('Csrf');
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
                        'db.identifier' => $this->validator->getValue('email'), 
                        'db.password'   => $this->validator->getValue('password'),
                    ],
                    $this->request->post('rememberMe')
                );

                if ($authResult->isValid()) {

                    $this->flash->success('You have authenticated successfully.')
                        ->response->redirect('/examples/membership/restricted');

                } else {
                    $this->form->setResults($authResult->getArray());
                }
            }
        }
        $this->view->load('login');
    }
}