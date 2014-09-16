<?php

/**
 * $app welcome
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('service/html');
        $c->load('view');

        // $c->load('cookie');
        // $cookie = array(
        //                    'name'   => 'sideWide',
        //                    'value'  => 'value',
        //                    'expire' => 86500,
        //                    'domain' => '.framework.com',
        //                    'path'   => '/',
        //                    'prefix' => 'myprefix_',
        //                    'secure' => false,
        //                    'httpOnly' => false
        //                );

        // // $this->cookie->set($cookie);

        // var_dump($_COOKIE);

    }
);

$app->func(
    'index',
    function () {
        $this->view->load(
            'welcome',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer', false));
            }
        );
    }
);

/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */