<?php

Class Start extends Controller {    
                                      
    function __construct()
    {   
        parent::__construct();
       
        loader::helper('ob/form');
        loader::helper('ob/form_send');
        loader::helper('ob/session');
        
        sess_start();
        
        if(db_item('username') == '')
        {
            show_error('<b>SETUP ERROR</b> - Please create a test database called <b>obullo</b> 
                and configure it from <b>/app/config/database.php</b>, then run the <b>test.sql</b> which is located in 
                your <b>/modules/welcome/</b> folder');
        }
        
        log_me('debug', '[ welcome ]: Example log, who want to keep the logs in different color.');
    }         

    public function index()
    {    
        $data = array();
        $data['user'] = array();
        
        view('view_form', $data, false);
    }
    
    function ajax_example()
    {
        $data = array();
        
        view('view_form_ajax', $data, false);
    }
    
    function do_post()
    {
        loader::model('user', false);  // Include user model
        
        $user = new User();
        $user->user_password = i_get_post('user_password');
        $user->user_email    = i_get_post('user_email');
        
        $data = array();
        $data['user'] = $user;  // User object for none ajax request
  
        if($user->save())
        {
            if($this->uri->extension() == 'json')  // Ajax request
            {
                echo form_send_success($user);
                return;
            }
            else    // Http request
            {
                sess_set_flash('notice', 'Form saved succesfully !');
                
                redirect('/welcome/start');
            }
        } 
        else
        {
            if($this->uri->extension() == 'json') // Ajax request
            {
                echo form_send_error($user);
                return;
            }
        }
        
        view('view_form', $data, false);    
    }
   
    public function delete()
    {
        loader::model('user', false);  // Include user model
        
        $user = new User();
        $user->where('usr_id', 1);
        
        if($user->delete())
        {
            echo 'User Deleted Successfuly !';
        } 
        
        print_r($user->errors());
    }
    
}

/* End of file start.php */
/* Location: .modules/test/controllers/start.php */