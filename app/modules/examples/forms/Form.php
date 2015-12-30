<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class Form extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->validator->setRules('email', 'Email', 'email|callback_test(7)|required');
            // $this->validator->setRules('iban', 'Iban', 'required|iban(TR)(false)');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required');

            $this->validator->func(
                'callback_test',
                function ($field, $params) {
                    $this->setMessage('Example callback error for email field !');
                    return true;
                }
            );
            if ($this->validator->isValid()) {          
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator);
        }

        $this->view->load('form');
    }
    
}