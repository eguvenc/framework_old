<?php

namespace Widgets\Tutorials;

class HelloElement extends \Controller
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
        $this->c['element'];
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

            if ($this->validator->isValid()) {
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator);
        }

        $this->c['view']->load('hello_element');
    }
    
}