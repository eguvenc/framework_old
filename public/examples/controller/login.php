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
        $c->load('service/user');
    }
);

$app->func(
    'index',
    function () use ($c) {

        $c->load('session');
        $c->load('service/cache');

        var_dump($this->session->get('__Auth/Identifier'));

        $this->user->activity->set('lastActivity', time());
        $this->user->activity->update();

        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'password', '123456');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:5445', 'username', 'mahmut');

        // // echo $this->cache->hGet('Test:__permanent:Authorized:eguvenc@gmail.com:5445', 'username');

        // $this->cache->hMSet('Test:__permanent:Authorized:eguvenc@gmail.com:5445', array('country' => 'tr', 'city' => 'adana' ));
        
        // print_r($this->cache->hGetAll('Test:__permanent:Authorized:eguvenc@gmail.com:5445'));

        // exit;

        // $data = $this->cache->getAllKeys('Test:__permanent:Authorized:eguvenc@gmail.com:*');

        // $all = $this->cache->hGetAll('Test:__permanent:Authorized:eguvenc@gmail.com:4454');
        // print_r($all);

        // print_r($data);
        // if (1413299033.04 > 1413299033.0571) {
        //     echo 'YES !!';
        // }

        
        // $data = $this->cache->getAllKeys('Auth:__permanent:Authorized:eguvenc@gmail.com:*');

        // var_dump($data); exit;

        // $this->session->remove('__Auth/Identifier');

        // var_dump($this->user->identity->logout());

        // var_dump($this->user->identity->isAuthenticated());

        // echo $this->user->activity->getMemoryBlockKey();

        echo '<a href="/examples/logout">logout</a>';

        echo '<pre>';

        var_dump($this->user->identity->isAuthenticated());

        print_r($this->user->identity->getArray());

        echo '</pre>';

        echo '<pre>';

        print_r($_SESSION);

        echo '</pre>';
        // unset($_SESSION['__Auth/Storage/Identifier']);

        // print_r($this->user->identity->getArray()); exit;
 
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
                    ),
                    $this->post['rememberMe']
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