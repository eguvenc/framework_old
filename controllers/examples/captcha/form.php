<?php

namespace Examples\Captcha;

Class Form extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('form');
        $this->c->load('view');
        $this->c->load('request');
        $this->c->load('service/captcha');
        $this->c->load('flash/session as flash');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->c->load('validator'); // load validator

            if (($code = $this->request->post('g-recaptcha-response')) === false) {
                $this->validator->setRules('captchaCode', 'Captcha', 'required|exact(5)|trim');
            }

            if (  ! $this->validator->isValid()) {
                $this->form->setErrors($this->validator);
            } else {

                $code = ($code !== false) ? $code : $this->validator->getValue('captchaCode');
                $result = $this->captcha->check($code);

                if ($result->isValid()) {

                    $this->flash->success('Captcha successful.');
                    $this->url->redirect('examples/captcha/form');

                } else {
                    $this->validator->setError($result->getArray());
                    $this->form->setErrors($this->validator);
                }
            }
        }

        $this->view->load(
            'form',
            function () {
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
}

/* End of file form.php */
/* Location: .controllers/examples/captcha/form.php */