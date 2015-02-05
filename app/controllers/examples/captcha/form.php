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
        $this->c['url'];
        $this->c['form'];
        $this->c['captcha'];
        $this->c['flash/session as flash'];
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        if ($this->c['request']->isPost()) {

            $this->c['validator']; // load validator

            if (  ! $this->validator->isValid()) {
                $this->form->setErrors($this->validator);
            } else {
                $result = $this->captcha->check();

                if ($result->isValid()) {

                    $this->flash->success('Captcha successful.');
                    $this->url->redirect('examples/captcha/form');

                } else {
                    $this->validator->setError($result->getArray());
                    $this->form->setErrors($this->validator);
                }
            }
        }

        $this->c['view']->load(
            'form',
            function () {
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
}

/* End of file form.php */
/* Location: .controllers/examples/captcha/form.php */
