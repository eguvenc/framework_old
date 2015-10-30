<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloAjax extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isAjax()) { // Is Ajax ?

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required|exact(1)');
            
            if ($this->validator->isValid()) {
                $this->validator->setError('email', 'Custom Error: There is an error in email field !');
                $this->form->error('There are some errors in form fields.');
            }
            $this->form->setErrors($this->validator);
            $this->response->json($this->form->outputArray());
            return;
        }

        $this->view->load(
            'hello_ajax',
            [
                'title' => 'Hello Ajax World !',
            ]
        );
    }
}