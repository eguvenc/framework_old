<?php

namespace Widgets\Tutorials;

class Hello_Captcha extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['form'];
        $this->c['request'];
        $this->c['view'];
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->c['validator'];
            
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('captcha_answer', 'Captcha', 'required|callback_captcha');

            $answer = $this->request->post('captcha_answer');

            $this->validator->func(
                'callback_captcha',
                function () {
                    if ($this->c['captcha']->check($answer) == false) {
                        $this->setMessage('callback_captcha', 'Wrong Captcha Code');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->validator->isValid()) {
                $this->form->success('Form Validation Success ! ');  // Set flash notice using Session Class.
            }
        }

        $this->view->load(
            'hello_captcha',
            [
                'title' => 'Hello Captcha !'
            ]
        );
    }
}