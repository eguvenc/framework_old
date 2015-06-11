<?php

namespace Widgets\Tutorials;

class HelloForm extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['view'];
        $this->c['form'];
        $this->c['request'];
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

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required');

            $this->validator->func(
                'callback_test',
                function ($email, $val) {
                    if (strlen($email) < $val) {
                        $this->setMessage('callback_test', 'Callback function validation test error !');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->validator->isValid()) {          
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator->getErrors());
        }

        $this->c['view']->load('hello_form');
    }
    
}