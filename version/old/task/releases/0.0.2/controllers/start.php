<?php
defined('STDIN') or die('Access Denied');

/**
 * $c start
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index', function(){

    echo "\33[1;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2014
Please run [$php task start help] You are in [ app / tasks ] folder.'."\n\033[0m";
});

/* End of file start.php */
/* Location: .app/tasks/controller/start.php */