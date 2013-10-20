<?php

Class Hello_World extends Controller {    
                                      
    function __construct()
    {
    	parent::__construct();

        // $schema = new Schema_Mysql('users');
        // echo nl2br($schema->create());
        // 
        // new Db();
        // $this->db->get('users');
    }

    function index()
    {
        view('hello_world',function() {

            $this->set('name', 'Obullo');
            $this->set('footer', tpl('footer', false));

        });
    }
}

/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */