<?php

/**
 * $c hello_world
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index', function() use($c){

	new Sess;
	// $this->sess->set('tess','aslkdjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj');
	// var_dump($this->db->resultArray());

	echo $this->sess->get('tess');

	// new Mongo_Db;
	// $this->mongo->get('users');

    $c->view('hello_world', function() use($c) {
        $this->set('name', 'Obullo');
        $this->set('footer', $c->tpl('footer', false));
    });
    
});

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */