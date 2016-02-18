<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class Element extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->validator->setRules('name', 'Name', 'required');
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('message', 'Your Message', 'required|max(800)');
            $this->validator->setRules('hear', 'Last', 'required');
            $this->validator->setRules('communicate', 'Communicate', 'required|max(5)');

            if ($this->validator->isValid()) {
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
        }
        
        $this->view->load('element');
    }
    
}