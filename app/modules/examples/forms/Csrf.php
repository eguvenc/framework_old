<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class Csrf extends Controller
{
    /**
     * Index
     *
     * @middleware->add('Csrf');
     * 
     * @return void
     */
    public function index()
    {
        if (! $this->config['extra']['annotations']) {
            throw new \RuntimeException('Annotations must be enabled in your configuration file.');
        }
        if ($this->request->isPost()) {

            $this->validator->setRules('email', 'Email', 'required|email|');
            $this->validator->setRules('password', 'Password', 'required|min(6)');

            if ($this->validator->isValid()) {          
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator->getErrors());
        }

        $this->view->load('csrf');
    }
    
}