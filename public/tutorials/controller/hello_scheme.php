<?php

/**
 * $c hello_scheme
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index',function() use($c){
    
    new Html;
    new Url;
    
    $c->tpl('default', function() use($c) {

        $this->set('name', 'Obullo');
        $this->set('title', 'Hello Scheme World !');
        $this->set('head', $this->html->css('welcome.css'));

        $this->getScheme($c->view('hello_scheme', false));
    });
});


/* End of file hello_scheme.php */
/* Location: .public/tutorials/controller/hello_scheme.php */