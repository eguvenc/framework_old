<?php

namespace Captcha\Examples;

class Ajax extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            $this->validator->setRules('username', 'Username', 'required|trim|max(100)');

            if ($this->validator->isValid()) {
                $this->form->success('Form Validation Success.');
            }
            $this->form->setErrors($this->validator);
            echo $this->response->json($this->form->outputArray());
            return;
        }
        $this->view->load(
            'ajax',
            [
                'title' => 'Hello Captcha !'
            ]
        );
    }
}