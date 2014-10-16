<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');

        $c->load('service/cache');

        $start = microtime(true);  // start

        // $a =array();
        // for ($i=0; $i < 100; $i++) { 
        //     $a['test__'.$i] = $i;
        // }
        // $this->cache->set('Auth:__permanent:Authorized:eguvenc@gmail.com', $a);

        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_1', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_2', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_3', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_4', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_5', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_6', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_7', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_8', 'ersin');
        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username_9', 'ersin');

        $end = microtime(true) - $start;  // End Timer

        echo '<br>'.number_format($end, 4);

    }
);

$app->func(
    'index',
    function () {

        $this->view->load(
            'hello_world',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer'));
            }
        );

    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */