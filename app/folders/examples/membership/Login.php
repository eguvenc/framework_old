<?php

namespace Examples\Membership;

use Obullo\Http\Controller;

class Login extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->validator->setRules('email', 'Email', 'required|email|trim');
            $this->validator->setRules('password', 'Password', 'required|min(6)|trim');

            if (! $this->validator->isValid()) {
                
                $this->form->error('Login error.');

            } else {

                $authResult = $this->user->login->attempt(
                    [
                        'db.identifier' => $this->validator->getValue('email'), 
                        'db.password'   => $this->validator->getValue('password'),
                    ],
                    [
                        'regenerateSessionId' => true,
                        'rememberMe' => $this->request->post('rememberMe')
                    ]
                );
                if ($authResult->isValid()) {

                    $this->flash->success('You have authenticated successfully.');
                    
                    return $this->response->redirect('/examples/membership/restricted');

                } else {

                    $this->form->setResults($authResult->getArray());
                }
            }
        }
        $this->view->load('login');
    }
}