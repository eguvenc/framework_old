<?php

Class Hello_World extends Controller {    
                                      
    function __construct()
    {
    	parent::__construct();
    }

    function index()
    {
        setVar('name', 'Obullo');

        // $this->config->load('test');
        // $this->config->item('base_url');

        // $this->security->xssClean('sd');
/*
        new Db();

        $this->db->select('user_email');
        $this->db->from('users');
        $this->db->get();
*/
        view('hello_world');
    }
}

/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */