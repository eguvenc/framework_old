<?php

defined('STDIN') or die('Access Denied');

/**
 * $app maintenance control
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
        case 'down':
            $this->_down();
            break;
        case 'up':
            $this->_up();
            break;
        case 'update':
            $this->_update();
            break;
        default:
            $this->_help();
            break;
        }
    }    
);

$app->func(
    '_down',
    function () use ($c) {
        $c->load('config/globals');
        $this->configGlobals->setValue('maintenance', array('app' => 'down'));
        $this->configGlobals->save();
        echo "\33[1;31mApplication is \"down\" for maintenance.\33[0m\n";
    }
);

$app->func(
    '_up',
    function () use ($c) {
        $c->load('config/globals');
        $this->configGlobals->setValue('maintenance', array('app' => 'up'));
        $this->configGlobals->save();
        echo "\33[1;32mApplication is \"up\".\33[0m\n";
    }
);

$app->func(
    '_update',
    function () use ($c) {
        // @todo version upgrage functions here
    }
);

$app->func(
    '_help',
    function () use ($c) {
        // @todo help functions here
    }
);


/* End of file app.php */
/* Location: .app/tasks/controller/app.php */