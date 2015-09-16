<?php

namespace Captcha\Examples;

class Form extends \Controller
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
            $this->validator->setRules('username', 'Username', 'required|trim|max(100)');

            if ($this->validator->isValid()) {
                $this->form->success('Form Validation Success.');
            }
        }
        $this->view->load(
            'form',
            [
                'title' => 'Hello Captcha !'
            ]
        );
    }
}