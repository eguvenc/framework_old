<?php

/**
 * $c hello_hmvc
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index',function() use($c){
    
    new Url;
    new Html;
	new Request;

    print_r($_SESSION);

    echo $this->sess->get('test');

    $c->view('hello_hmvc', function() use($c){

        $this->set('response_a', $this->request->get('tutorials/hello_dummy/test/1/2/3'));
        $this->set('response_b', $this->request->get('tutorials/hello_dummy/test/4/5/6'));

        $this->set('name', 'Obullo');
        $this->set('footer', $c->tpl('footer', false));

    });
});

/* End of file hello_hmvc.php */
/* Location: .public/tutorials/controller/hello_hmvc.php */