<?php

Class Hello_World extends Controller {    
                                      
    function __construct()
    {
    	parent::__construct();
    }

    function index()
    {
        view('hello_world',function() {
            $this->set('name', 'Obullo');
            $this->set('footer', tpl('footer', false));
        });

        view('hello_world',function() {
            $this->set('odm_form', function(){

                echo Form::open('/tutorials/odm', array('method' => 'post'), 'default');

                    echo Form::label('Password'); 
                    echo Form::error('password');
                    echo Form::password('password', '', " id='password' ");

                    echo Form::break();

                    echo Form::label('Password'); 
                    echo Form::error('confirm_password');
                    echo Form::password('confirm_password', '', " id='confirm' ");
                    
                echo Form::close();

            });

        });
}

/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */