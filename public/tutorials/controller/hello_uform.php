<?php

Class Hello_Uform extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new Uform();
    }

    function index()
    {      
        $this->uform->create('div', function(){

            $this->addForm('/tutorials/hello_form', array('method' => 'post'));

            $this->addRow(); 
            $this->addCol(array(
                'label' => 'Email',
                'input' => $this->input('email', $this->setValue('email'), " id='email' "),
                'rules' => 'required|xssClean'
                )
            );
            


            $this->addRow(); 
            $this->addCol(array(
                'label' => 'Password',
                'input' => $this->input('pass', $this->setValue('pass'), '', " id='password' "),
                'rules' => 'required|xssClean'
                )
            );
            $this->addCol(array(
                'label' => 'Select', 'input' => $this->dropdown("selectbox",array(1=> "Yes" , 2 => "No" , 3 => "I don't know"))
            ));



            $this->addRow(); 
            $this->addCol(array(
                'input' => $this->submit('submit', ' Login ', '', " id='password' ")
                )
            );
        });
        
        $this->uform->isValid();
        
        $uform = $this->uform->printForm();

        // $form = getConfig('form');

        // $form['errors']['error']

        view('hello_uform', function() use($uform) {

            $this->set('name', 'Obullo Uform');
            $this->set("uform", $uform);
            $this->set('footer', tpl('footer', false));
        });
    }
    
    function doPost()
    {
        $this->user->email    = Get::post('email');
        $this->user->password = Get::post('password');

        $this->user->setRule('confirm_password', array('rules' => 'required|matches(password)'));
        $this->user->setRule('agreement', array('label' => 'User Agreement', 'rules' => 'integer|required'));
       
        $this->user->func('save', function() {
            if ($this->isValid())
            {
                $this->password = md5($this->values('password'));
                $this->db->insert('users', $this);
                return true;
            }
            return false;
        });

        if($this->user->save())
        {
            $this->user->setNotice('User saved successfully.');

            Url::redirect('tutorials/hello_form');
        }

        view('hello_form', function() {
            $this->set('form_html', null);
        });
    }

}


/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */