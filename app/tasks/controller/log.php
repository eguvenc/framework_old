<?php

defined('STDIN') or die('Access Denied');

/**
 * $app log
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function ($route = 'app') {
        $this->_displayLogo();
        $this->_follow($route);
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

        Welcome to Log Manager v2.0 (c) 2014
You are displaying the "app" request logs. To change direction use $php task log "ajax" or "cli".'."\n\033[0m";

    }
);

/**
 * Print colorful log messages to your console.
 * 
 * @param $route request type
 */ 
$app->func(
    '_follow',
    function ($route) use ($c) {
        $followerName = 'Obullo\Cli\LogFollower\\'.ucfirst($this->logger->getWriterName());
        $follower = new $followerName;
        $follower->follow($c, $route);
    }
);

/* End of file log.php */
/* Location: .app/tasks/controller/log.php */