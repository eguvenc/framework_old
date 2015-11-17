<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloForm extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            // oku
            // https://github.com/php-fig/fig-standards/issues/507

            // var_dump($this->request->getParsedBody());

            $this->validator->setRules('email', 'Email', 'callback_test(7)|required|email|');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required');

            $this->validator->func(
                'callback_test',
                function ($field, $value) {
                    $this->setMessage('Callback function validation test error !');
                    return false;
                }
            );
            if ($this->validator->isValid()) {          
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator->getErrors());
        }

        $this->view->load('hello_form');
    }
    
}