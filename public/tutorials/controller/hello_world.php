<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');

        // $this->db->query('SELECT * FROM users');

        // $c->load('session');
        // $c->load('service/cache');

        // $start = microtime(true);  // Run Timer

        // echo hash('adler32', $_SERVER['HTTP_USER_AGENT']);

        // $end = microtime(true) - $start;  // End Timer
        // echo '<br>'.number_format($end, 12);

        // $c->load('service/provider/cache as cache', array('serializer' => 'SERIALIZER_NONE'));

        // $redis = new Redis();
        // $redis->connect($this->config['cache']['servers'][0]['hostname'], $this->config['cache']['servers'][0]['port']);

        // $this->cache->set('test', 'testValue0988888888');
        // echo $this->cache->get('test');

        // $this->session->set('test_', 'test');
        // echo $this->session->get('test_').'<br>';  // 811 1500 5000 596 1621 1407 620 878   

        // for ($i=0; $i < 1000; $i++) {

        //     // $redis->set('test_'.$i, 'test_'.$i);
        //     echo $redis->del('test_'.$i).'<br>'; 

        //     // $this->session->set('test_'.$i, 'test_'.$i);
        //     // echo $this->session->get('test_'.$i).'<br>';  // 811 1500 5000 596 1621 1407 620 878   
        // }

        // $this->session->set('test', 'testValuezzzzzzzzz');
        // echo $this->session->get('test');  // 811 1500 5000 596 1621 1407 620 878


        // $end = microtime(true) - $start;  // End Timer

        // echo '<br>'.number_format($end, 4);

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