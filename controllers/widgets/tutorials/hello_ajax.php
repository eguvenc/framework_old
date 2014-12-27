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
        $this->c->load('form');
        $this->c->load('request');
        $this->c->load('response');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isAjax()) { // Is Ajax ?

            $this->c->load('validator');

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required|exact(1)');
            
            if ($this->validator->isValid()) {
                $this->validator->setError('email', 'Custom Error Example: There is an error in email field !');
                $this->form->setMessage('There are some errors in form fields.');
            }
            $this->form->setErrors($this->validator);
            echo $this->response->json($this->form->outputArray());
            return;
        }

        $this->c->load('url');
        $this->c->load('view');

        $this->view->load(
            'hello_ajax',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('title', 'Hello Ajax World !');
                $this->layout('welcome');
            }
        );
    }
}


/* End of file hello_ajax.php */
/* Location: .controllers/tutorials/hello_ajax.php */