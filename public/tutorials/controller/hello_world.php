<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('rate/limiter as limiter');

        $this->limiter->load('username');        
        $this->limiter->username->channel('login');
        $this->limiter->username->identifier('user@example.com');
        
        if ($this->limiter->username->isAllowed()) {
            
            echo 'Allowed User !';

            $fail = true; // If some operation failed ?

            if ($fail) {
                $this->limiter->username->reduce();     // We reduce user request limit if operation fail. ( e.g. login attempt )
            } else {
                $this->limiter->username->increase();   // We increase user request limit if operation success.
            }
        }

        if ($this->limiter->username->isBanned()) {
            echo 'Maximum request limit reached user is banned !';
        }


        // $this->limiter->username->increaseLimit();
        

        // $this->limiter->username->removeBan();
        
        echo $this->limiter->username->getError();

        // $this->limiter->username->reduceLimit();

        
        // var_dump($this->config->xml()->route->site);
        // $this->config->save();
        // print_r($c->load('app')->getEnvArray());
        $start = microtime(true);  // start

        // $c->load('service/queue');

        // $this->queue->channel('Log');
        // $this->queue->push('Workers/Logger', 'Server1.logger', array('log' => array('debug' => 'Test')));
        // $this->queue->push('Workers/Logger', 'Server1.logger', array('message' => 'This is my message'));
        // $this->queue->push('Workers/Logger', 'Server1.logger', array('log' => array('debug' => 'Test')));

        // $this->logger->emergency('TEST attempt !', array('username' => 'test2'));

        // // // $this->logger->load('mongo'); 
        
        // $this->logger->load('mongo')->filter('priority.notIn', array(LOG_EMERG));
        // $this->logger->alert('Possible hack attempt !', array('username' => 'test2'));
        // $this->logger->emergency('MOGO ATTTEMPT !', array('username' => 'test2'));
        // $this->logger->push();

        // $this->logger->mongo->channel();
        // $this->logger->mongo->alert();

        // $this->logger->load('email');
        // $this->logger->email->channel();
        // $this->logger->email->alert();

        // $this->logger->mongo->channel('test');
        // $this->logger->mongo->log('alert', '');

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