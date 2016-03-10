<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class Ajax extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required|exact(1)');
            $this->validator->setRules('hear', 'Last', 'required');
            $this->validator->setRules('communicate', 'Communicate', 'required|max(5)');

            if ($this->validator->isValid()) {          

                // isValid Errors.
                // 
                // $this->validator->setError('email', 'Custom Error: There is an error in email field !');
                // $this->form->error('There are some errors in form fields.');

                $this->form->success('Form validation success.');

            } else {
                
                $this->form->error('Form validation failed.');
            }

            return $this->response->json($this->form->getOutputArray());
        }

        $this->view->load('ajax');
    }

}