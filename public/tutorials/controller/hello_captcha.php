<?php

/**
 * $o hell_captcha
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('url');
        $c->load('html');
        $c->load('form');
        $c->load('post');
    }
);

$app->func(
    'index',
    function () use ($c) {

        if ($this->post['dopost']) {

            $c->load('validator');
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('captcha_answer', 'Captcha', 'required|callback_captcha');

            $this->validator->func(
                'callback_captcha',
                function () use ($c) {
                    if ($c->load('captcha')->check($this->post['captcha_answer']) == false) {
                        $this->setMessage('callback_captcha', 'Wrong Captcha Code');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->validator->isValid()) {
                $this->form->setMessage('Form Validation Success ! ');  // Set flash notice using Session Class.
            }
        }
        $this->view->load(
            'hello_captcha',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('title', 'Hello Captcha !');
                $this->getScheme('welcome');
            }
        );

    }
);


/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */