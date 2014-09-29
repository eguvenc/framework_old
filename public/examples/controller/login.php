<?php

/**
 * $app login
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('form');
        $c->load('view');
        $c->load('post');
        $c->load('service/auth/user');
    }
);

$app->func(
    'index',
    function () use ($c) {

        var_dump($this->user->identity->isGuest());

        print_r($this->user->identity->getArray());

        if ($this->post['dopost']) {

            $c->load('validator'); // load validator

            $this->validator->setRules('email', 'Email', 'required|email|trim');
            $this->validator->setRules('password', 'Password', 'required|min(6)|trim');

            if ($this->validator->isValid()) {

                // $this->user->login->enableVerification();

                $result = $this->user->login->attempt(
                    array(
                        Auth\Credentials::IDENTIFIER => $this->validator->value('email'), 
                        Auth\Credentials::PASSWORD => $this->validator->value('password')
                    )
                );

                if ($result->isValid()) {

                    print_r($result->getArray());

                } else {

                    $this->validator->setError($result->getArray());

                    // print_r($result->getCode());
                    ///print_r($result->getIdentifier());
                }

            }
            $this->form->setErrors($this->validator);
            // $this->form->setErrors($result->getMessages());

        }

        $this->view->load(
            'login',
            function () {
                $this->assign('footer', $this->template('footer'));
            }
        );

    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */