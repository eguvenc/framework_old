<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloElement extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required');

            if ($this->validator->isValid()) {
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator);
        }

        $this->view->load('hello_element');
    }
    
}