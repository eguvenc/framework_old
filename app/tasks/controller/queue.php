<?php

defined('STDIN') or die('Access Denied');

/**
 * $app queue
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () use ($c) {
        $c->load('cli/parser');

        $this->cliParser->parse(func_get_args());
        switch ($this->cliParser->segment(0)) {
        case 'list':
            $this->_list();
            break;
        case 'listen':
            $this->_listen();
            break;
        default:
            $this->_help();
            break;
        }
    }    
);

$app->func(
    '_displayLogo',
    function () {
            echo "\33[1;36m".'
            ______  _            _  _
           |  __  || |__  _   _ | || | ____
           | |  | ||  _ || | | || || ||  _ |
           | |__| || |_||| |_| || || || |_||
           |______||____||_____||_||_||____|

            Welcome to Task Manager (c) 2014
    You are running $php task queue command which is located in app / tasks folder.'."\n\033[0m\n";
    }
);

$app->func(
    '_help',
    function () {

            echo $this->_displayLogo();
            echo "\33[1;36mUsage:\33[0m\33[0;36m
    php task [command] [arguments]\n\33[0m\n";

            echo "\33[1;36mAvailable commands:\33[0m\33[0;36m
    list       : List queued jobs.
    listen     : Wait and send jobs to job handler.\33[0m\n";

    }
);

$app->func(
    '_list',
    function () use ($c) {

        echo $this->_displayLogo();
        echo "\33[0;36mFollowing queue data ...\33[0m\n";

        $c->load('queue');

        $argument = $this->cliParser->argument('channel');
        $channel = $this->queue->exchange($argument);           // Sets queue channel
        $route   = $this->cliParser->argument('route', null);  // Sets queue route key ( queue name )
        $deleteJob = $this->cliParser->argument('delete');

        echo $route;
        $break = "------------------------------------------------------------------------------------------";
        echo "\033[1;36m".$break."\n\033[0m";

        while (true) {
            $job = $this->queue->pop($route);  // Get the last message from queue but don't mark it as delivered
            if ( ! is_null($job)) {
                echo "\033[1;33m".$job->getJobId().' - '.$job->getRawBody()."\033[0m\n";
                if ($deleteJob == 'delete') {  // Delete all jobs in the queue
                     $job->delete();
                }
            }
        }
        sleep(1);
    }
);

/**
 * php task queue listen --channel=Logger --route=server1.log --memory=128 --delay=0 --timeout=3
 */
$app->func(
    '_listen',
    function () use ($c) {

        $c->load('queue/worker');
        $this->queueWorker->init();

        // while (true) {
        //     $this->queueWorker->pop();
        // }
        // do job.
    }
);


/* End of file help.php */
/* Location: .app/tasks/controller/queue.php */