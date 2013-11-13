<?php

Class Welcome extends Controller {

    function __construct()
    {
        parent::__construct();

        // new Acl();
        // 
        // new Db('db');        
        // 
        // print_r($this->db->get('users')->resultArray());
        // var_dump($this->ftp);

        // echo Form::open('asdas/sadasd', " class='asd ' "); 

    }
    
    public function index()
    {   
        view('welcome', function(){
            $this->set('name', 'Obullo');
        }); 
    }
}

/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */