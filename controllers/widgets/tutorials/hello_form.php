<?php

namespace Widgets\Tutorials;

Class Hello_Form extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('view');
        $this->c->load('form');
        $this->c->load('request');
        $this->c->load('session');
        $this->c->load('flash/session as flash');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {
            
            $this->c->load('validator');

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

                // $this->validator->setError('email', 'Example Error !');                
                $this->form->success('Example form success message in current page !');
                
                var_dump($this->form->getStatus());

                // $this->flash->success('Example flash notice please refresh this page !');   // Set flash notice
                // $this->url->redirect('tutorials/hello_form/index'); // Redirect to user same page using header refresh.

            } else {

                $this->form->error('Form validation failed !');
            }
            $this->form->setErrors($this->validator->getErrors());
        }

        $this->view->load(
            'hello_form', 
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
    
}

/* End of file hello_form.php */
/* Location: .controllers/tutorials/hello_form.php */