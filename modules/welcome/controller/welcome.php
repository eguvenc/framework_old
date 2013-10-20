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
    }
    
    public function index()
    {
/*
        $modelName  = 'User';
        $schemaName = $modelName.'\Schema';
            
        foreach(array_keys(get_object_vars(new $schemaName())) as $key)
        {
            echo $key;
        }
*/
        
        view('welcome', function(){
            $this->set('name', 'Obullo');
        }); 
    }
}

/* End of file welcome.php */
/* Location: .modules/welcome/controller/welcome.php */