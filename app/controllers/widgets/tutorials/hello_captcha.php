<?php

namespace Widgets\Tutorials;

Class Hello_Captcha extends \Controller
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

            $this->validator->func(
                'callback_captcha',
                function () {
                    if ($this->c['captcha']->check($this->request->post('captcha_answer')) == false) {
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
            function () {
                $this->assign('title', 'Hello Captcha !');
                $this->layout('welcome');
            }
        );
    }
}


/* End of file hello_captcha.php */
/* Location: .controllers/tutorials/hello_captcha.php */