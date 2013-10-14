<?php

Class Welcome extends Controller {

    function __construct()
    {
        parent::__construct();
        // new Email();
        // new Form_Json::start();
        // new Acl();
      
        // new Db();
        // print_r($this->db->get('users')->resultArray());
        // var_dump($this->ftp);
    }
    
    public function index()
    {
        setVar('name', 'Obullo');
/*
        $modelName  = 'User';
        $schemaName = $modelName.'\Schema';
            
        foreach(array_keys(get_object_vars(new $schemaName())) as $key)
        {
            echo $key;
        }
*/

        // $fields = $this->db->showColumns('users')->result();
        // print_r($fields);

        // print_r($res->getColumnMeta(1));

        // echo $this->uri->uriString();
        // echo $this->uri->requestUri();
        // new request\start();
        
        // sess\set('test', 1234);
        // echo sess\get('test');
        
        View::get('welcome'); 
    }
}

/* End of file welcome.php */
/* Location: .modules/welcome/controller/welcome.php */