<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class HelloCsrf extends Controller
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

            if ($this->validator->isValid()) {          
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }

            $this->form->setErrors($this->validator->getErrors());
        }

        $this->view->load('hello_csrf');
    }
    
}