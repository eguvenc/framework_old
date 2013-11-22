<?php

/**
 * $c welcome
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
});

$c->func('index', function() use($c){

    $c->view('welcome', function() use($c) {
        $this->set('name', 'Obullo');
        $this->set('footer', $c->tpl('footer', false));
    });
    
});

/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */