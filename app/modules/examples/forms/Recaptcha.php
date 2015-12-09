<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class ReCaptcha extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->validator->bind($this->captcha);
            $this->validator->setRules('email', 'Email', 'required|trim|email|max(100)');

            if ($this->validator->isValid()) {
                $this->form->success('Form Validation Success.');
            }
        }
        $this->view->load('captcha');
    }
}