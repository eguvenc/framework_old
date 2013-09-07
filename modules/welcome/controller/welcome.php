<?php

Class Welcome extends Controller {
    
    function __construct()
    {
        parent::__construct();
        
        // new Email();
        // new form_Json\start();
     
        // new Acl();
        // new Db();
      
        // print_r($this->db->get('users')->resultArray());
        
        // var_dump($this->ftp);
    }
    
    public function index()
    { 
        vi\setVar('name', 'Obullo');
        // new request\start();
       
        
        // echo request\get('welcome/test/1/2/3');
        // echo request\get('welcome/test/4/5/6');
          
        // $user  = new \Model\User();
        // $user->test();
        // $user  = new Model\User();
        // $email = new Email(false);
        // $this->db->get('users');
        // new Auth();
        
        // new sess\start();
        
        // sess\set('test', 1234);
        // echo sess\get('test');
        vi\view('welcome');
    }
}

/* End of file welcome.php */
/* Location: .modules/welcome/controller/welcome.php */