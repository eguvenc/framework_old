<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {

        // $c->load('service/jelly/form as jelly');
        // $c->load('service/rbac/user');

        // var_dump($this->jelly);
        $c->load('url');
        $c->load('service/auth');
        $c->load('view');

        // var_dump($this->mongo);

        // var_dump($a);
        // var_dump($b);

        // var_dump($this->serviceProviderMongo);

        // $closure = function () {
        //     new User\Active;
        // };
        // $a = $closure();
        // $a();
        // $a();
        // $a();

        // $this->sess->set('user_id', 6445445);

        // $this->sess->regenerateId(false);

        // $this->sess->rememberMe(0);
        // echo $this->sess->get('session_id').'<br>';
        // echo $this->sess->get('hello');

        // Load ın içine koymaya calis !!!!!!!!!!!!!!!!!!


        // $test = $this->sess->get('hello');
        // echo $test;

        // $db = $c->newProvider('database', array('db.name' => 'queue_jobs'));
        // $db->update('failures', array('job_id' => 1), array('id' => 8, 'job_id' => 1), ' LIMIT 1');
        // $db->delete('failures', array(), ' WHERE job_id LIKE '.$this->db->escapeLike(4));
        $id = uniqid();

        // echo $a;

        // $c['queue']->exchange('ObulloLog');
        // $c['queue']->push('SendLog', array('id' => $id, 'log' => array('debug' => 'test')), $routingKey = 'Server1.logger');
        // $c['queue']->push('SendLog', array('id' => $id, 'message' => 'this is my message'), $routingKey = 'Logger');
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