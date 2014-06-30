<?php

/**
 * $app hello_ajax
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('form');
        $c->load('request');
        $c->load('response');
    }
);

$app->func(
    'index',
    function () use ($c) {

        if ($this->request->isXmlHttp()) { // Is Ajax ?

            $c->load('validator');
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required|exact(1)');

            if ($this->validator->isValid()) {
                $this->validator->setError('email', 'Custom Error Example: There is an error in email field !');
                $this->form->setMessage('There are some errors in form fields.');
            }
            $this->form->setErrors($this->validator);
            echo $this->response->json($this->form->getOutput());
            return;
        }
        $c->load('url');
        $c->load('html');
        $c->load('view');

        $this->view->load(
            'hello_ajax',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('title', 'Hello Ajax World !');
                $this->getScheme('welcome');
            }
        );

    }
);

/* End of file hello_ajax.php */
/* Location: .public/tutorials/controller/hello_ajax.php */