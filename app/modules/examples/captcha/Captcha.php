<?php

namespace Examples\Captcha;

use RuntimeException;
use Obullo\Http\Controller;

class Captcha extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if (! $this->c->has('captcha')) {
            throw new RuntimeException("Captcha service is not defined in your components.");
        }
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