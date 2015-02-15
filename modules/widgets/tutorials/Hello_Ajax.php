<?php

namespace Widgets\Tutorials;

Class Hello_Ajax extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['form'];
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->c['request']->isAjax()) { // Is Ajax ?

            $this->c['validator'];

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required|exact(1)');
            
            if ($this->validator->isValid()) {
                $this->validator->setError('email', 'Custom Error Example: There is an error in email field !');
                $this->form->error('There are some errors in form fields.');
            }
            $this->form->setErrors($this->validator);
            echo $this->c['response']->json($this->form->outputArray());
            return;
        }
        $this->c['url'];

        $this->c['view']->load(
            'hello_ajax',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('title', 'Hello Ajax World !');
                $this->layout('welcome');
            }
        );
    }
}