<?php

defined('STDIN') or die('Access Denied');

/**
 * $c clear
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () {
        $this->_clear();  // Start the Clear Task
    }
);

$app->func(
    '_clear',
    function () use ($c) {
        
        $files = array(
            trim($c->load('config')['log']['path']['app'], '/'),
            trim($c->load('config')['log']['path']['ajax'], '/'),
            trim($c->load('config')['log']['path']['cli'], '/'),
        );
        foreach ($files as $file) {
            $file = str_replace('/', DS, $file);
            if (strpos($file, 'data') === 0) { 
                $file = str_replace('data', rtrim(DATA, DS), $file);
            } 
            $exp      = explode(DS, $file);
            $filename = array_pop($exp);
            $path     = implode(DS, $exp). DS;
            if (is_file($path.$filename)) {
                unlink($path.$filename);
            }
        }
        echo "\33[1;36mApplication log files deleted.\33[0m\n";
    }
);

/* End of file clear.php */
/* Location: .app/tasks/controller/clear.php */