<?php

namespace Examples\Recaptcha;

use RuntimeException;
use Obullo\Http\Controller;

class Recaptcha extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if (! $this->c->has('recaptcha')) {
            throw new RuntimeException("Recaptcha service is not defined in your components.");
        }
        if ($this->request->isPost()) {

            $this->validator->setRules('email', 'Email', 'required|trim|email|max(100)');
            $this->validator->setRules('recaptcha', 'Captcha', 'reCaptcha');

            if ($this->validator->isValid()) {
                $this->form->success('Form Validation Success.');
            }
        }
        $this->view->load('recaptcha');
    }
}