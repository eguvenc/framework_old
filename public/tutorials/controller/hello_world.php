<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');

        // var_dump($this->config->xml()->route->site);
        // $this->config->save();
        // print_r($c->load('app')->getEnvArray());

        $start = microtime(true);  // start

        // $a =array();
        // for ($i=0; $i < 100; $i++) { 
        //     $a['test__'.$i] = $i;
        // }
        // $this->cache->set('Auth:__permanent:Authorized:eguvenc@gmail.com', $a);

        // $this->cache->hSet('Test:__permanent:Authorized:eguvenc@gmail.com:4454', 'username', 'ersin');


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