<?php

namespace Examples\Forms;

use RuntimeException;
use Obullo\Http\Controller;

class Csrf extends Controller
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
            $this->validator->setRules('csrf_token', 'Csrf Token', 'csrf');

            if ($this->validator->isValid()) {
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator);
        }

        $this->view->load('csrf');
    }
}