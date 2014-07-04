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
        $c->load('service/queue');

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
    function () {

        echo $this->_displayLogo();
        $break = "------------------------------------------------------------------------------------------";

        $channel = $this->cliParser->argument('channel');
        $route   = $this->cliParser->argument('route', null);  // Sets queue route key ( queue name )
        $remove  = $this->cliParser->argument('clear');

        echo "\33[0;36mFollowing queue data ...\33[0m\n\n";
        echo "\33[1;36mChannel : ".$channel."\33[0m\n";
        echo "\33[1;36mRoute   : ".$route."\33[0m\n";

        $this->queue->channel($channel);  // Sets queue exchange
        echo "\033[1;36m".$break."\33[0m\n";
        echo "\033[1;36m".' Job ID | Job Name             | Data '."\33[0m\n";
        echo "\033[1;36m".$break."\33[0m\n";

        $lines = '';
        while (true) {
            $job = $this->queue->pop($route);  // !!! Get the last message from queue but don't mark it as delivered
            if ( ! is_null($job)) {
                $raw = json_decode($job->getRawBody(), true);
                $jobIdRepeat = 6 - strlen($job->getJobId());  // 999999
                if (strlen($job->getJobId()) > 6) {
                    $jobIdRepeat = 6;
                }
                $jobNameRepeat = 20 - strlen($raw['job']);
                if (strlen($raw['job']) > 20) {
                    $jobNameRepeat = 20;
                }
                $lines = "\033[1;36m ".$job->getJobId().str_repeat(' ', $jobIdRepeat).' | ';
                $lines.= $raw['job'].str_repeat(' ', $jobNameRepeat).' | ';;
                $lines.= json_encode($raw['data']);
                $lines.= "\33[0m\n";
                echo $lines;
                if ($remove == 'clear') {  // Delete all jobs in the queue
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